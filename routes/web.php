<?php

use App\Http\Controllers\GlobalController;
use Illuminate\Support\Facades\Route;

Route::post('validate/{?input}', GlobalController::class)->name('index');
