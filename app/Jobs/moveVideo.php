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
use NotificationChannels\Discord\Discord;

class moveVideo implements ShouldQueue
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
        $dossier = storage_path("app/public");
        $episode = Episodes::find($this->episodes->id);
        $serie = Serie::find($episode->serie_id);
        $saison = Saisons::find($episode->saisons_id);
        $taille = [];
        if ($episode->etat == 1){
            for ($i = 1; $i <= 10; $i++)
            {
                $taille[$i] = filesize($dossier.'/'.$this->fichier);
                $taille[$i+1] = 0;
                sleep(100);


                if ($taille[1] == $taille[2]){
                    if ($episode->etat == 1){
                        Storage::disk('public')->move($this->fichier, "serie/$serie->type/$serie->slug/videos/$episode->id/$this->fichier");
                        verifVideo::dispatch($episode,$this->user, $this->fichier);
                        $episode->etat = 3;
                        $episode->save();
                    }

                    break;
                }else{
                    if ($i == 2){
                        $i =0;
                    }
                    //$channel = app(Discord::class)->send($discord, ['content' => "$taille[1] / $taille[2]"] );

                }

            }
        }



    }
}
