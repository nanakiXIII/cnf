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
        $discord = 253979896303321089;
        $episode = Episodes::find($this->episodes->id);
        $serie = Serie::find($episode->serie_id);
        $saison = Saisons::find($episode->saisons_id);
        $taille = [];

        for ($i = 1; $i <= 10; $i++)
        {
            $taille[$i] = filesize($dossier.'/'.$this->fichier);
            $taille[$i+1] = 0;
            sleep(60);


            if ($taille[1] == $taille[2]){
                $array = ["embed" =>['title'=>"[EN] $serie->titre $saison->type $saison->numero: $episode->type $episode->numero ",
                    'description' => "Encodage terminé $taille[1] / $taille[2]",
                    'author' =>['name' => $this->user->name,
                        'icon_url' => 'https://image.chuushin-no-fansub.fr/avatar/733296.gif'],
                    'thumbnail' => ['url' => env('APP_URL').$serie->image]]];
                $channel = app(Discord::class)->send($discord, $array );
                break;
            }else{
                if ($i = 2){
                    $i =1;
                }
                $channel = app(Discord::class)->send($discord, ['content' => "$taille[1] / $taille[2]"] );

            }

        }

    }
}
