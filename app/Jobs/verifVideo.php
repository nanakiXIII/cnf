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
use Illuminate\Support\Facades\Storage;

class verifVideo implements ShouldQueue
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
        $discord = 253979896303321089;
        $episode = Episodes::find($this->episodes->id);
        $serie = Serie::find($episode->serie_id);
        $saison = Saisons::find($episode->saisons_id);
        $file = Storage::disk('public')->exists("serie/$serie->type/$serie->slug/videos/$episode->id/$this->fichier");
         if ($file){
             $episode->etat = 4;
             $episode->streaming = "stream";
             Storage::delete($episode->id.'.mkv');
         }

    }
}