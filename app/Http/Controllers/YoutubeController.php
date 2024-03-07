<?php

namespace App\Http\Controllers;

use getID3;
use getid3_writetags;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;
use Illuminate\Support\Str;

class YoutubeController extends Controller
{
    public function generateMp3DownloadLink($id, $filename)
    {
        $token = Str::random(16); // Generate a random token
        // Save the token and other details in the database for validation later
        // You may want to associate it with the user, URL, and name
        // ...

        $link = route('index.yt-download-mp3', ['id' => $id, 'filename' => $filename, 'token' => $token]);

        return $link;
    }

    public function download($id) {
        if (Storage::disk('public')->exists('mp3/'.$id.'/metadata')) {
            foreach (Storage::disk('public')->allFiles('mp3/'.$id.'/metadata') as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'json') {
                    $video = json_decode(Storage::disk('public')->get($file));
                    $notfound = false;
                    return view('y2mate.download', compact('video', 'notfound'));
                    break;
                }
            }
        }
        $yt = new YoutubeDl();
       // $yt->setBinPath('/opt/homebrew/bin/youtube-dl');
        $collection = $yt->download(
            Options::create()
                ->maxFileSize('150m')
                ->skipDownload(true)
                ->cleanupMetadata(false)
                ->downloadPath(storage_path('app/public/mp3/'.$id.'/metadata/'))
                ->url('https://www.youtube.com/watch?v='.$id)
        );

        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                $notfound = true;
                return view('y2mate.download', compact('video', 'notfound'));
            } else {
            }
        }

        $notfound = false;
        return view('y2mate.download', compact('video', 'notfound'));
    }

    public function convert_api(Request $request) {
        $id = $request->id;
        if (Storage::disk('public')->exists('mp3/'.$id.'/file')) {
            foreach (Storage::disk('public')->allFiles('mp3/'.$id.'/file') as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'mp3') {
                    $pathinfo = pathinfo($file);
            
                    return response()->json(
                    [
                            "title" =>
                                $pathinfo['basename'],
                            "link" =>
                                $this->generateMp3DownloadLink(Crypt::encryptString($id), Crypt::encryptString($pathinfo['basename'])),
                            "duration" => '-',
                            "msg" => "success",
                            "status" => "ok",
                            "age" => "0",
                            "progress" => 0,
                            "filesize" => '-',
                    ]);
                    break;
                }
            }
        } else {
            $yt = new YoutubeDl();
            $yt->setBinPath('/usr/local/bin/yt-dlp');
            $collection = $yt->download(
                Options::create()
                    ->downloadPath(storage_path('app/public/mp3/'.$id.'/file'))
                    ->extractAudio(true)
                    ->audioFormat('mp3')
                    // ->preferFFmpeg(true)
                    // ->ffmpegLocation('/usr/bin/ffmpeg')
                    ->audioQuality('0')
                    ->output('%(title)s.%(ext)s')
                    ->url('https://www.youtube.com/watch?v='.$id)
            );

            foreach ($collection->getVideos() as $video) {
                if ($video->getError() !== null) {
                    echo "Error downloading video: {$video->getError()}.";
                } else {
                    $url = $video->getFile()->getPathname();

                    $getID3 = new getID3;

                    $tagwriter = new getid3_writetags;
                    $tagwriter->filename = $url;
                    $tagwriter->tagformats = array('id3v2.4');
                    $tagwriter->overwrite_tags    = true;
                    // $tagwriter->remove_other_tags = true;
                    $tagwriter->tag_encoding      = 'UTF-8';

                    $pictureFile = file_get_contents("https://i.ytimg.com/vi/".$id."/default.jpg");

                    $TagData = array(
                        'attached_picture' => array(
                            array (
                                'data'=> $pictureFile,
                                'picturetypeid'=> 3,
                                'mime'=> 'image/jpeg',
                                'description' => $video->getFile()->getFilename()
                            )
                        )
                    );

                    $tagwriter->tag_data = $TagData;

                    // write tags
                    if ($tagwriter->WriteTags()){

                        return response()->json(
                            [
                                "title" =>
                                    $video->getTitle(),
                                "link" =>
                                    $this->generateMp3DownloadLink(Crypt::encryptString($video->getId()), Crypt::encryptString($video->getFile()->getFilename())),
                                "duration" => $video->getDuration(),
                                "msg" => "success",
                                "status" => "ok",
                                "age" => "0",
                                "progress" => 0,
                                "filesize" => $video->getFilesize(),
                            ]);

                    }else{
                        throw new \Exception(implode(' : ', $tagwriter->errors));
                    }
                }
            }
            
        }

    }

    public function convert(Request $request) {
        $id = $request->vid;
        $yt = new YoutubeDl();
        $collection = $yt->download(
            Options::create()
                ->downloadPath(public_path('mp3'))
                ->extractAudio(true)
                ->audioFormat('mp3')
                ->audioQuality('0')
                ->output('%(title)s.%(ext)s')
                // ->writeThumbnail(true)
                // ->writeAllThumbnails(true)
                ->url('https://www.youtube.com/watch?v='.$id)
        );

        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                echo "Error downloading video: {$video->getError()}.";
            } else {
                $url = $video->getFile()->getPathname();

                $getID3 = new getID3;

                $tagwriter = new getid3_writetags;
                $tagwriter->filename = $url;
                $tagwriter->tagformats = array('id3v2.4');
                $tagwriter->overwrite_tags    = true;
                // $tagwriter->remove_other_tags = true;
                $tagwriter->tag_encoding      = 'UTF-8';

                $pictureFile = file_get_contents("https://i.ytimg.com/vi/".$id."/default.jpg");

                $TagData = array(
                    'attached_picture' => array(
                        array (
                            'data'=> $pictureFile,
                            'picturetypeid'=> 3,
                            'mime'=> 'image/jpeg',
                            'description' => $video->getFile()->getFilename()
                        )
                    )
                );

                $tagwriter->tag_data = $TagData;

                // write tags
                if ($tagwriter->WriteTags()){

                    return response()->json([
                        "status" => "ok",
                        "mess" => "",
                        "c_status" => "CONVERTED",
                        "vid" => $id,
                        "title" => $video->getTitle(),
                        "ftype" => "mp3",
                        "fquality" => "128",
                        "dlink" => $this->generateMp3DownloadLink(Crypt::encryptString($video->getId()), Crypt::encryptString($video->getFile()->getFilename()))
                    ]);

                }else{
                    throw new \Exception(implode(' : ', $tagwriter->errors));
                }
            }
        }

    }

    public function download_mp3($id, $filename) {
        return response()->download(storage_path('app/public/mp3/'.Crypt::decryptString($id)).'/file/'.Crypt::decryptString($filename));
    }
    
    public function analyze(Request $request) {
        $url = $request->k_query;
        $menu = $request->menu;
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->acceptJson()->post('http://192.53.116.208:9009/api/json',[
            'url' => $url,
            "aFormat" => "mp3",
            "filenamePattern" => "classic",
            "dubLang" => false,
            "isAudioOnly" => true,
            "isNoTTWatermark" => true
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
            "vid" => $posts['metadata']['attribute']['id'], 
            "extractor" => "youtube", 
            "title" => $posts['metadata']['attribute']['title'], 
            "t" => 4403, 
            "a" => $posts['metadata']['attribute']['author'],  
            "links" => [
                  "mp3" => [
                                    "mp3128" => [
                                          "size" => "63.6 MB", 
                                          "f" => "mp3", 
                                          "q" => "128kbps", 
                                          "q_text" => "MP3 - 128kbps", 
                                          "k" => $this->generateMp3DownloadLink($posts['metadata']['attribute']['id'], $posts['metadata']['attribute']['title'])
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
