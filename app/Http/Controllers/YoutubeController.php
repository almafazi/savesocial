<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class YoutubeController extends Controller
{
    public function index() {

        $yt = new YoutubeDl();

        $collection = $yt->download(
            Options::create()
                ->downloadPath('/path/to/downloads')
                ->url('https://www.youtube.com/watch?v=oDAw7vW7H0c')
        );

        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                echo "Error downloading video: {$video->getError()}.";
            } else {
                echo $video->getTitle(); // Will return Phonebloks
                // $video->getFile(); // \SplFileInfo instance of downloaded file
            }
        }
    }
}
