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
        sleep(5);
        $path = storage_path('app/public/'.$this->episodes->hd);
        \Zipper::make($path)->extractTo(storage_path('app/public/serie/'.$this->episodes->serie_id.'/'.$this->episodes->saisons_id.'/'.$this->episodes->id.'/'));
    }
}
