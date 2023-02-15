<?php

use Illuminate\Support\Facades\Route;
use tian\weatherapi\Controllers\WeatherController;

//changed from 'right' route method to get for views
Route::get('/weatherapi/get/{user:id}', [WeatherController::class, 'show']);
Route::match(['put', 'post'], '/weatherapi/set/{user:id}', [WeatherController::class, 'store']);
Route::delete('/weatherapi/delete/{user:id}/{UserCity:id}', [WeatherController::class, 'destroy']);
