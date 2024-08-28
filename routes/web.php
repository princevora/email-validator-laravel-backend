<?php

use App\Http\Controllers\GlobalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['as' => 'api.'], function(){
    Route::post('api/validate', GlobalController::class)->name('validate');
})->prefix('api/');
