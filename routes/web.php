<?php

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

Route::get('/', function () {
    return view('home');
});

Route::prefix('doctrine')->group(function () {
    Route::get('/cache/stats', 'DoctrineController@cacheStats');
    Route::get('/cache/{target}/clear', 'DoctrineController@cacheClear')
        ->where('target', 'query|metadata');
});
