<?php

use App\Http\Controllers\GlobalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->name('api.')->group(function(){
    Route::post('validate', GlobalController::class)->name('validate');
});