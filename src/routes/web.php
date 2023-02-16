<?php

use Illuminate\Support\Facades\Route;
use tian\weatherapi\Controllers\WeatherController;


Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/weatherapi/', [WeatherController::class, 'show'])
        ->name('weather.get');

    Route::match(['put', 'post'], '/weatherapi/', [WeatherController::class, 'store'])
        ->name('weather.set');

    Route::delete('/weatherapi/{UserCity:id}', [WeatherController::class, 'destroy'])
        ->name('weather.delete');
});
