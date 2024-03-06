<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class YoutubeController extends Controller
{
    public function analyze(Request $request) {
        $url = $request->k_query;
        $menu = $request->menu;
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->acceptJson()->post('http://192.53.116.208:9009/api/json',[
            'url' => $url
        ]);

        $posts = $response->collect();


        if($posts['status'] == 'error') {
            return response()->json([
                "error" => "YT URL is not valid!"
            ]);
        };

        if($posts->count() == 0) {
            return response()->json([]);
        };

        $posts = $posts->toArray();

        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        $youtube_id = $match[1];

        return response()->json([
            "status" => "ok", 
            "mess" => "", 
            "page" => "detail", 
            "vid" => $youtube_id, 
            "extractor" => "youtube", 
            "title" => $posts['metadata']['title'], 
            "t" => 4403, 
            "a" => $posts['metadata']['artist'],  
            "links" => [
                  "mp3" => [
                                    "mp3128" => [
                                          "size" => "63.6 MB", 
                                          "f" => "mp3", 
                                          "q" => "128kbps", 
                                          "q_text" => "MP3 - 128kbps", 
                                          "k" => $posts['url']
                                       ] 
                                 ] 
            ], 
            "related" => [
                [
                    "title" => "Related Videos", 
                    "contents" => [
                    ] 
                ] 
            ] 
         ]); 


       

    }
}
