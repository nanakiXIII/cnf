<?php

namespace App\Jobs;

use App\Episodes;
use App\Saisons;
use App\Serie;
use FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class imageVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Episodes
     */
    private $episodes;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Episodes $episodes)
    {
        //
        $this->episodes = $episodes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $discord = 253979896303321089;
        $episode = Episodes::find($this->episodes->id);
        $serie = Serie::find($episode->serie_id);
        $saison = Saisons::find($episode->saisons_id);
        if ($episode) {

            $video = FFMpeg::fromDisk('public')->open($episode->id.'.mkv');
            $time = $video->getDurationInSeconds();
            $temp = round($time / 15);
            for ($i = 5; $i < $time; $i += $temp) {
                $video->getFrameFromSeconds($i)->export()
                    ->toDisk('public')
                    ->save("serie/$serie->type/$serie->slug/videos/$episode->id/images/$i.jpg");
            }
            $temps = $temp + 5;
            $episode->image = "/storage/serie/$serie->type/$serie->slug/videos/$episode->id/images/$temps.jpg";
            $episode->save();
        }
    }
}
