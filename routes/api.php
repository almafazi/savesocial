<?php

use App\Http\Controllers\API\InstagramController;
use App\Http\Controllers\API\TiktokController;
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

Route::get('posts/{shortcode}', [InstagramController::class, 'post']);
Route::post('fetch', [InstagramController::class, 'fetch'])->name('instagram.fetch');
Route::post('tiktok-fetch', [TiktokController::class, 'fetch'])->name('tiktok.fetch')->middleware('throttle:1,0.2');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
