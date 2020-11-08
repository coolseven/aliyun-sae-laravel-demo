<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    public function inspire(Request $request)
    {
        Cache::increment('cache:inspired-visitors');
        $inspiredVisitors = (int) Cache::get('cache:inspired-visitors');

        $quote = Inspiring::quote();

        Log::info('new request arrived.', [
                'request' => $request->__toString(),
                'quote' => $quote,
                'inspiredVisitors' => $inspiredVisitors,
            ]
        );

        return view('inspire',[
            'quote' => $quote,
            'inspiredVisitors' => $inspiredVisitors,
        ]);
    }
}
