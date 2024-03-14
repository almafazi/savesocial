<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TiktokDownloadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(isset($_GET['musiclink'])) {
            $this->download_mp3($_GET['musiclink'], $_GET['author']);
        } else if(isset($_GET['imglink'])) {
            $this->download_image($_GET['imglink'], $_GET['author']);
        } else if (isset($_GET['link'])) {
            $this->download($_GET['link'], $_GET['author']);
        } else {
            die(404);
        }
    }
    
    function download($url, $name) {
        $decrypted = base64_decode($url);
        $filename= trim($name).' '.date('d-m-Y Hi').' '.uniqid().'.mp4';
        $url = $decrypted;
        $headers = get_headers($url, 1);
        header('Content-Length: '.$headers["Content-Length"]);
        header("Content-Transfer-Encoding: Binary");
        header('Content-Type: '.$headers["Content-Type"]); 
        header("Content-disposition: attachment; filename=\"$filename\""); 
    
        echo readfile($url);

    }

    function download_mp3($url, $name) {
        $decrypted = base64_decode($url);
        $filename= trim($name).' '.date('d-m-Y Hi').' '.uniqid().'.mp3';
        $url = $decrypted;
        $headers = get_headers($url, 1);
        header('Content-Length: '.$headers["Content-Length"]);
        header("Content-Transfer-Encoding: Binary");
        header('Content-Type: '.$headers["Content-Type"]); 
        header("Content-disposition: attachment; filename=\"$filename\""); 
    
        echo readfile($url);

    }

    function download_image($url, $name) {
        $decrypted = base64_decode($url);
        $filename= trim($name).' '.date('d-m-Y Hi').' '.uniqid().'.jpeg';
        $url = $decrypted;
        $headers = get_headers($url, 1);
        header('Content-Length: '.$headers["Content-Length"]);
        header("Content-Transfer-Encoding: Binary");
        header('Content-Type: '.$headers["Content-Type"]); 
        header("Content-disposition: attachment; filename=\"$filename\""); 
    
        echo readfile($url);

    }
}
