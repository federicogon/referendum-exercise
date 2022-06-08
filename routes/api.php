<?php

use Illuminate\Http\Request;
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


Route::post('/referendum/create', [\App\Http\Controllers\Referendum::class, 'create']);
Route::get('/referendum/questions', [\App\Http\Controllers\Referendum::class, 'questions']);
Route::post('/referendum/vote', [\App\Http\Controllers\Referendum::class, 'vote']);
Route::get('/referendum/results/{id}', [\App\Http\Controllers\Referendum::class, 'results']);
Route::get('/referendum/results{sort?}', [\App\Http\Controllers\Referendum::class, 'allResults']);
