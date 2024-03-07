<?php

use App\Http\Controllers\API\TiktokController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\YoutubeController;
use App\Http\Middleware\RateLimitDownload;
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

// // Auth Routes
// require __DIR__.'/auth.php';

// // Language Switch
// Route::get('language/{language}', [LanguageController::class, 'switch'])->name('language.switch');

// Route::get('dashboard', 'App\Http\Controllers\Frontend\FrontendController@index')->name('dashboard');
/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'App\Http\Controllers\Frontend', 'as' => 'frontend.'], function () {
    //Route::get('/ytdl', [YoutubeController::class, 'index'])->name('index.ytdl');

    Route::get('/', function() {
        return view('y2mate/home', [
            'menu' => null
        ]);
    })->name('index.y2mate');

    Route::post('mates/analyzeV2/ajax', [YoutubeController::class, 'analyze'])->name('index.y2mate.analyze');
    Route::post('mates/convertV2/index', [YoutubeController::class, 'convert'])->name('index.y2mate.convert');

    Route::get('/yt-download-mp3/{id?}/{filename?}', [YoutubeController::class, 'download_mp3'])->name('index.yt-download-mp3');
    Route::get('/yt-convert-mp3/{id?}', [YoutubeController::class, 'convert_mp3'])->name('index.yt-convert-mp3');

    Route::get('y2mate/download/{id}', [YoutubeController::class, 'download'])->name('index.y2mate.download');
    Route::post('y2mate/convert', [YoutubeController::class, 'convert_api'])->name('index.y2mate.convert');


});