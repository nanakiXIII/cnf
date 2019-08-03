<?php

namespace App\Jobs;

use App\Episodes;
use Chumper\Zipper\Zipper;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ExtractArchiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Episodes
     */
    private $episodes;

    /**
     * Create a new job instance.
     *
     * @param Episodes $episodes
     */
    public function __construct(Episodes $episodes)
    {

        $this->episodes = $episodes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = storage_path('app/public/'.$this->episodes->hd);
        $dossier = 'serie/'.$this->episodes->serie_id.'/'.$this->episodes->saisons_id.'/'.$this->episodes->id.'/';
        \Zipper::make($path)->extractTo(storage_path('app/public/serie/'.$this->episodes->serie_id.'/'.$this->episodes->saisons_id.'/'.$this->episodes->id.'/'));
        $directories = Storage::disk('public')->directories($dossier);
        if (count($directories) == 1){
            $files = Storage::disk('public')->files($directories[0]);
            $dossier = $directories[0];
        }
        else{
            $files = Storage::disk('public')->files($dossier);
        }
        $episode = Episodes::find($this->episodes->id);
        $episode->hd = '/storage/'.$episode->hd;
        $episode->image = '/storage/'.$files[0];
        $episode->etat  = 5;
        $episode->streaming = $dossier;
        $episode->save();
    }
}
