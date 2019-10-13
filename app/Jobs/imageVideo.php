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
            broadcast(new userEvent($this->user, ['episode' => $episode, 'data' => "Images"]));
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
            broadcast(new userEvent($this->user, ['episode' => $episode, 'data' => "Image terminé"]));
        }
    }
}
