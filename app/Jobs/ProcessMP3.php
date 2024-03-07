<?php

namespace App\Jobs;

use getID3;
use getid3_writetags;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class ProcessMP3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $yt = new YoutubeDl();
            $yt->setBinPath('/opt/homebrew/bin/youtube-dl');
            $collection = $yt->download(
                Options::create()
                    ->downloadPath(storage_path('app/public/mp3/'.$this->id.'/file'))
                    ->extractAudio(true)
                    ->audioFormat('mp3')
                    // ->preferFFmpeg(true)
                    // ->ffmpegLocation('/usr/bin/ffmpeg')
                    ->audioQuality('0')
                    ->output('%(title)s.%(ext)s')
                    ->url('https://www.youtube.com/watch?v='.$this->id)
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

                    $pictureFile = file_get_contents("https://i.ytimg.com/vi/".$this->id."/default.jpg");

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

                    }else{
                        throw new \Exception(implode(' : ', $tagwriter->errors));
                    }
                }
            }
    }
}
