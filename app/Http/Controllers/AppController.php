<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppController extends Controller
{
    public function inspire(Request $request)
    {
        $inspire = Inspiring::quote();

        Log::info('new request arrived.', [
                'request' => $request->__toString(),
                'inspire' => $inspire,
            ]
        );

        return view('inspire',[
            'inspire' => $inspire,
        ]);
    }
}
