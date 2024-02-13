<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TiktokController extends Controller
{
    public function generateDownloadLink($url, $name)
    {
        $token = Str::random(16); // Generate a random token
        // Save the token and other details in the database for validation later
        // You may want to associate it with the user, URL, and name
        // ...

        $link = route('frontend.index.tiktok-download-image', ['url' => $url, 'name' => $name, 'token' => $token]);

        return $link;
    }

    public function fetch(Request $request) {
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


        if($posts['status'] != 'picker' && $posts['status'] != 'error') {

            $posts['video_data']["nwm_video_url"] = $this->generateDownloadLink(Crypt::encryptString($posts['metadata']['play_addr'][0]), $posts['metadata']['author']);
            $posts['video_data']["wm_video_url"] = $this->generateDownloadLink(Crypt::encryptString($posts['metadata']['download_addr'][0]), $posts['metadata']['author']);
    
            unset($posts["metadata"]["download_addr"]);
            unset($posts["metadata"]["play_addr"]);
        } else if($posts['status'] == 'picker') {
            $posts['picker'] = collect($posts['picker'])->map(function($item) {
                $item['download_url'] = Crypt::encryptString($item['url']);
                return $item;
            })->toArray();
        }

        return response()->json($posts);
    }

    public function download($url, $name) {
        $decrypted = Crypt::decryptString($url);
        $filename= $name.' '.date('d-m-Y Hi').' '.uniqid().'.mp4';
        $url = $decrypted;
        $headers = get_headers($url, 1);
        header('Content-Length: '.$headers["Content-Length"]);
        header("Content-Transfer-Encoding: Binary");
        header('Content-Type: '.$headers["Content-Type"]); 
        header("Content-disposition: attachment; filename=\"$filename\""); 
       
        echo readfile($url);

    }

    public function download_image($url, $name) {
        $decrypted = Crypt::decryptString($url);
        $filename= $name.' '.date('d-m-Y Hi').' '.uniqid().'.jpeg';
        $url = $decrypted;
        $headers = get_headers($url, 1);
        header('Content-Length: '.$headers["Content-Length"]);
        header("Content-Transfer-Encoding: Binary");
        header('Content-Type: '.$headers["Content-Type"]); 
        header("Content-disposition: attachment; filename=\"$filename\""); 
       
        echo readfile($url);

    }
}
