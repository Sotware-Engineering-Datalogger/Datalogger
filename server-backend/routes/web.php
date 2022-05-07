<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/**
 * Sensor data controller routes
 */
Route::controller(\App\Http\Controllers\SensorDataController::class)->group(function () {
    Route::get('/sensor/temperature', 'indexTemperature');
    Route::get('/sensor/humidity', 'indexHumidity');
    Route::get('/sensor/light', 'indexLight');
    Route::get('/sensor/pressure', 'indexPressure');

    Route::post('/sensor/temperature', 'indexTemperature');
    Route::post('/sensor/humidity', 'indexHumidity');
    Route::post('/sensor/light', 'indexLight');
    Route::post('/sensor/pressure', 'indexPressure');
});
