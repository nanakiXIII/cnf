<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Transformers\ActivityTransformer;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Array_;

class userEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var User
     */
    private $user;
    /**
     * @var String
     */
    public $param;
    /**
     * @var
     */
    private $test;
    /**
     * @var array
     */
    private $info;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $param)
    {
        $this->user = $user;
        $this->param = $param;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('App.User.'.$this->user->id);
    }
}
