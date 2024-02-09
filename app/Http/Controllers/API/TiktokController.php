<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class TiktokController extends Controller
{
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

        if($posts['status'] != 'picker') {

            $posts['video_data']["nwm_video_url"] = Crypt::encryptString($posts['metadata']['play_addr'][0]);
            $posts['video_data']["wm_video_url"] =  Crypt::encryptString($posts['metadata']['download_addr'][0]);
    
            unset($posts["metadata"]["download_addr"]);
            unset($posts["metadata"]["play_addr"]);
        }

        return response()->json($posts);
    }

    public function download($url, $name) {
        $decrypted = Crypt::decryptString($url);
        $filename= $name.' '.date('d-m-Y Hi').' '.uniqid().'.mp4';
        $url = $decrypted;
        $size = $this->get_size($url);
        header('Content-Length: '.$size);
        header("Content-Transfer-Encoding: Binary");
        header('Content-Type: application/octet-stream'); 
        header("Content-disposition: attachment; filename=\"$filename\""); 
       
        echo readfile($url);

    }

    public function get_size($url) {
        $my_ch = curl_init();
        curl_setopt($my_ch, CURLOPT_URL,$url);
        curl_setopt($my_ch, CURLOPT_HEADER, true);
        curl_setopt($my_ch, CURLOPT_NOBODY, true);
        curl_setopt($my_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($my_ch, CURLOPT_TIMEOUT, 10);
        $r = curl_exec($my_ch);
        foreach(explode("\n", $r) as $header) {
            if(strpos($header, 'Content-Length:') === 0) {
                return trim(substr($header,16));
            }
        }
        return '';
    }
}
