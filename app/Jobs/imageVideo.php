<?php

namespace App\Jobs;

use App\Episodes;
use App\Events\userEvent;
use App\Saisons;
use App\Serie;
use App\User;
use FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use NotificationChannels\Discord\Discord;

class imageVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Episodes
     */
    private $episodes;
    /**
     * @var
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Episodes $episodes, User $user)
    {
        //
        $this->episodes = $episodes;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $episode = Episodes::find($this->episodes->id);
        if ($episode) {
           $array = ["embed" =>['title'=>"[Extraction des images] $episode->serie->titre $episode->type $episode->numero",
                    'author' =>['name' => 'Martel'],
                    'thumbnail' => ['url' => env('APP_URL').'storage/images/images/'.$episode->serie->image]]];
           $channel = app(Discord::class)->send(env('Log'), $array );
            $video = FFMpeg::fromDisk('public')->open("serie/$episode->serie_id/$episode->saisons_id/$episode->id/$episode->id.mkv");
            $time = $video->getDurationInSeconds();
            $temp = round($time / 15);
            for ($i = 5; $i < $time; $i += $temp) {
                $video->getFrameFromSeconds($i)->export()
                    ->toDisk('public')
                    ->save("serie/$episode->serie_id/$episode->saisons_id/$episode->id/images/$i.jpg");
            }
            $temps = $temp + 5;
            $episode->image = "/storage/serie/$episode->serie_id/$episode->saisons_id/$episode->id/images/$temps.jpg";
            $episode->save();
            $array = ["embed" =>['title'=>"[Extraction terminé] $episode->serie->titre $episode->type $episode->numero",
                                'author' =>['name' => 'Martel'],
                                'thumbnail' => ['url' => env('APP_URL')."storage/serie/$episode->serie_id/$episode->saisons_id/$episode->id/images/100.jpg"]
                                ]
                    ];
           $channel = app(Discord::class)->send(env('Log'), $array );
        }
    }
}
