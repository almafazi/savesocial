<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = Storage::disk("public")->allDirectories("mp3");

        foreach ($files as $file) {
            $time = Storage::disk('public')->lastModified($file);
            $fileModifiedDateTime = Carbon::parse($time);
            $fileModifiedDateTime->setTimezone('Asia/Jakarta');
            if (Carbon::now()->setTimezone('Asia/Jakarta')->gt($fileModifiedDateTime->addHours(6))) {
                Storage::disk("public")->deleteDirectory($file);
            }
        }
             
         
    }
}
