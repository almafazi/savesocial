<?php

use App\Http\Controllers\YoutubeController;
use Illuminate\Support\Facades\Route;

    Route::get('yt2mate', function() {
        return view('y2mate/home', [
            'menu' => null
        ]);
    })->name('index.y2mate');

    Route::post('mates/analyzeV2/ajax', [YoutubeController::class, 'analyze'])->name('index.y2mate.analyze');
    Route::post('mates/convertV2/index', [YoutubeController::class, 'convert'])->name('index.y2mate.convert');

    Route::get('/yt-download-mp3/{id?}/{filename?}', [YoutubeController::class, 'download_mp3'])->name('index.yt-download-mp3');

    Route::get('downloader/{id}', [YoutubeController::class, 'download'])->name('index.y2mate.download');
    Route::post('y2mate/convert', [YoutubeController::class, 'convert_api'])->name('index.y2mate.convert');
