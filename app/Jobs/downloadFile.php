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

class downloadFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Episodes
     */
    private $episodes;
    /**
     * @var string
     */
    private $qualiter;
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Episodes $episodes, String $qualiter, User $user)
    {
        //
        $this->episodes = $episodes;
        $this->qualiter = $qualiter;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url ="http://cnfddl.mass-download.net/";
        $episode = Episodes::find($this->episodes->id);
        if ($episode){
            $serie = Serie::find($episode->serie_id);
            $saison = Saisons::find($episode->saisons_id);
            $extension = pathinfo($episode[$this->qualiter], PATHINFO_EXTENSION);
            $basename = pathinfo($episode[$this->qualiter], PATHINFO_BASENAME);
            $filename = pathinfo($episode[$this->qualiter], PATHINFO_FILENAME);
            $file = file_get_contents($url.$episode[$this->qualiter]);
            $save = file_put_contents(storage_path('app/public/'.$episode->id.'.'.$extension), $file);
            if ($save){
                $discord = 253979896303321089;
                $array = ["embed" =>['title'=>"[DL] $serie->titre $saison->type $saison->numero: $episode->type $episode->numero ",
                    'description' => 'Téléchargement terminé',
                    'author' =>['name' => $this->user->name,
                        'icon_url' => 'https://image.chuushin-no-fansub.fr/avatar/733296.gif'],
                    'thumbnail' => ['url' => env('APP_URL').$serie->image]]];
                $channel = app(Discord::class)->send($discord, $array );
            }

        }
    }
}
