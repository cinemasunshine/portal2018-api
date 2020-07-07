<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('schedules/{type}', 'Api\ScheduleController@index')
    ->where('type', 'now-showing|coming-soon');
Route::resource('schedules', 'Api\ScheduleController')->only([
    'show',
]);
