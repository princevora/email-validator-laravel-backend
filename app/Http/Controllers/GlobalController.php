<?php

namespace App\Http\Controllers;

use App\Classes\DnsHandler;
use App\Classes\Handler;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, DnsHandler $dns)
    {
        return (new Handler($request, $dns))->run();
    }
}
