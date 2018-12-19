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
        $discord = 253979896303321089;
        $array = ["content"=>"coucou je suis la"];
        $channel = app(Discord::class)->send($discord, $array );
    }
}
