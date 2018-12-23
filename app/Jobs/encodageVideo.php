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
        $discord = 253979896303321089;
        $episode = Episodes::find($this->episodes->id);
        $serie = Serie::find($episode->serie_id);
        $saison = Saisons::find($episode->saisons_id);
        $array = ["embed" =>['title'=>"[DL] $serie->titre $saison->type $saison->numero: $episode->type $episode->numero ",
            'description' => "Début de l'encodage",
            'author' =>['name' => $this->user->name,
                'icon_url' => 'https://image.chuushin-no-fansub.fr/avatar/733296.gif'],
            'thumbnail' => ['url' => env('APP_URL').$serie->image]]];
        $channel = app(Discord::class)->send($discord, $array );
        $basename = pathinfo($this->fichier, PATHINFO_BASENAME);
        $filename = pathinfo($this->fichier, PATHINFO_FILENAME);
        $dossier = storage_path("app/public");
        chdir($dossier);
        $shell = shell_exec("ffmpeg -i $basename -vf subtitles=$basename -strict -2 $filename.mp4");

        if ( !is_null($shell)){
            $array = ["embed" =>['title'=>"[DL] $serie->titre $saison->type $saison->numero: $episode->type $episode->numero ",
                'description' => "Encodage terminé",
                'author' =>['name' => $this->user->name,
                    'icon_url' => 'https://image.chuushin-no-fansub.fr/avatar/733296.gif'],
                'thumbnail' => ['url' => env('APP_URL').$serie->image]]];
            $channel = app(Discord::class)->send($discord, $array );
        }

    }
}
