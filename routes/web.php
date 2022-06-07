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
    return view('welcome');
});

Route::get('/voter', [App\Http\Controllers\HomeController::class, 'voter']);
Route::post('/vote', [App\Http\Controllers\HomeController::class, 'vote']);
Route::get('/results', [App\Http\Controllers\HomeController::class, 'results']);

