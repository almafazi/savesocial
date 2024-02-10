<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class TiktokController extends Controller
{
    public function index(Request $request) {
        if($request->isMethod('post')) {
            $url = $request->url;
        
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->acceptJson()->post('http://192.53.116.208:9009/api/json',[
                'url' => $url
            ]);
    
            $posts = $response->collect();
    
            if($posts->count() == 0) {
                return response()->json([]);
            };
    
            $posts = $posts->toArray();
    
            if($posts['status'] != 'picker') {
    
                $posts['video_data']["nwm_video_url"] = Crypt::encryptString($posts['metadata']['play_addr'][0]);
                $posts['video_data']["wm_video_url"] =  Crypt::encryptString($posts['metadata']['download_addr'][0]);
        
                unset($posts["metadata"]["download_addr"]);
                unset($posts["metadata"]["play_addr"]);
            }
    
            return view('home-tiktok', compact('posts'));
        } else {
            return view('home-tiktok');
        }
        
    }
}
