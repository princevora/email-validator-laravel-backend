<?php

namespace App\Http\Controllers;

use App\Classes\Handler;
use Illuminate\Http\Request;

class GlobalController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return (new Handler($request))->run();
    }
}
