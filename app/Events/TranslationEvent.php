<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\User;

class TranslationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $user;
    public $message;
    public $id;
    public $room_id;
    public $from_user;
    public $img;
//    public function __construct($user, $room_id, $from_user )
    public function __construct($id, $message, $from_user, $img )
    {

        $this->message = $message;
        $this->id = $id;
        $this->from_user = $from_user;
        $this->img = $img;

//        $this->room_id = $room_id;
//        $this->user = $user;
//        $this->user = auth()->guard('api')->user()->id;


//        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
//    public function broadcastWith($user)
//    {
// return [$user => auth()->guard('api')->user()];
//
//    }


    public function broadcastOn()
    {
//        return new PrivateChannel('translation.'.$this ->room_id);
        return new PrivateChannel('translation.'.$this ->id);
    }


}
