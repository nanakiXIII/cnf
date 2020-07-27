<?php

namespace App\Jobs;

use App\Episodes;
use App\Saisons;
use App\Serie;
use App\User;
use FFMpeg\FFMpeg;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
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
        $url =env('URL_DL');
        $episode = Episodes::find($this->episodes->id);
        $array = ["embed" =>['title'=>"[Téléchargement] ".$episode->serie->titre." $episode->type $episode->numero",
                                         'author' =>['name' => $this->user->name,
                                         'icon_url' => env('APP_URL').$this->user->avatar],
                                         'thumbnail' => ['url' => env('APP_URL').'/storage/images/images/'.$episode->serie->image]]];
        $channel = app(Discord::class)->send(env('Log'), $array );
        if ($episode){
            if ($episode->etat == 0){
                if ($episode->dvd != 'non'){
                    $fichier = $episode->dvd;
                    $qualiter = 'dvd';
                }
                elseif($episode->hd != 'non'){
                    $fichier = $episode->hd;
                    $qualiter = 'hd';
                }
                else{
                    $fichier = $episode->fhd;
                    $qualiter = 'fhd';
                }
                $extension = pathinfo($fichier, PATHINFO_EXTENSION);
                $basename = pathinfo($fichier, PATHINFO_BASENAME);
                $filename = $episode->id.'.'.$extension;
                $file = file_get_contents($url.$fichier);
                $save = file_put_contents(storage_path("app/public/".$filename), $file);
                $episode->etat = 1;
                $episode->save();
                if ($save){
                    $save = Storage::disk('public')->move($filename, "serie/$episode->serie_id/$episode->saisons_id/$episode->id/$filename");
                    Storage::disk('public')->delete($filename);
                    $episode->etat = 2;
                    $episode->save();
                    if ($episode->etat == 2){
                        $array = ["embed" =>['title'=>"[Téléchargement terminé] ".$episode->serie->titre." $episode->type $episode->numero",
                                                         'author' =>['name' => $this->user->name,
                                                         'icon_url' => env('APP_URL').$this->user->avatar],
                                                         'thumbnail' => ['url' => env('APP_URL').'/storage/images/images/'.$episode->serie->image]]];
                        $channel = app(Discord::class)->send(env('Log'), $array );
                        imageVideo::dispatch($episode);
                        encodageVideo::dispatch($episode, $this->user, $filename);
                    }

                }
            }

        }
    }
}
