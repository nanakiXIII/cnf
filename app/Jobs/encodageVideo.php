<?php

namespace App\Jobs;

use App\Episodes;
use App\Saisons;
use App\Serie;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use NotificationChannels\Discord\Discord;
use PhpParser\Node\Scalar\String_;

class encodageVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Episodes
     */
    private $episodes;
    /**
     * @var User
     */
    private $user;
    /**
     * @var String
     */
    private $fichier;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Episodes $episodes, User $user, String $fichier)
    {
        //
        $this->episodes = $episodes;
        $this->user = $user;
        $this->fichier = $fichier;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $basename = pathinfo($this->fichier, PATHINFO_BASENAME);
        $filename = pathinfo($this->fichier, PATHINFO_FILENAME);
        $episode = Episodes::find($this->episodes->id);
        if ($episode->etat == 2){
            $dossier = storage_path("app/public/serie/$episode->serie_id/$episode->saisons_id/$episode->id/");
            chdir($dossier);
            $episode->etat = 3;
            $episode->save();
            $shell = shell_exec("ffmpeg -i $basename -vf subtitles=$basename -strict -2 $filename.mp4");
        }
        if (file_exists($dossier.'/'.$filename.'.mp4')){
            $episode->etat = 4;
            $episode->save();
            //moveVideo::dispatch($episode, $this->user, "$filename.mp4");
        }
        else{

        }
    }
}
