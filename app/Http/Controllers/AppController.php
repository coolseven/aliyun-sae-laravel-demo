<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    public function inspire(Request $request)
    {
        // 一句名人名言
        $quote = Inspiring::quote();

        // 调用第三方的 api, 获取中文翻译
        try {
            $quoteInChinese = app('cache')->remember(
                'cache:translation:md5_'.md5($quote),
                random_int(50,70),
                function() use ($quote) {
                    $translation = app('translate')->translate($quote, 'en','zh-CHS');
                    return ($translation['translation'][0]);
                }
            );
        } catch (Exception $exception) {
            $quoteInChinese = '[Translation Service Error] ' . $exception->getMessage();
        }

        // 访问 redis
        $inspiredVisitors = (int) Cache::get('cache:inspired-visitors');
        Cache::increment('cache:inspired-visitors');

        // 访问 log 系统
        Log::info('new request arrived.', [
                'request' => $request->__toString(),
                'quote' => $quote,
                'inspiredVisitors' => $inspiredVisitors,
            ]
        );

        if ($request->wantsJson()) {
            return new JsonResponse([
                'quote' => $quote,
                'quoteInChinese' => $quoteInChinese,
                'inspiredVisitors' => $inspiredVisitors,
            ]);
        }

        return view('inspire',[
            'quote' => $quote,
            'quoteInChinese' => $quoteInChinese,
            'inspiredVisitors' => $inspiredVisitors,
        ]);
    }
}
