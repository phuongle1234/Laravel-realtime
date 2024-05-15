<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;
use Carbon\Carbon;

class MessageSentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $_user_id_send;
    public $_user_id;
    public $_message_id;
    public $_message;
    public $_path;

    public function __construct(
                                    int $user_id_send,
                                    int $user_id,
                                    int $message_id,
                                    string $message,
                                    $path = null
                                )
    {
        $this->_user_id_send = $user_id_send;
        $this->_user_id = $user_id;
        $this->_message_id = $message_id;
        $this->_message = $message;
        $this->_path = $path;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PresenceChannel('chat.'.$this->_user_id_send);
        return new PrivateChannel('App.Models.User.'.$this->_user_id_send);
    }

    public function broadcastWith()
    {

        $_count = Contact::where('user_id', $this->_user_id_send)->whereNull('seen_at')->count();

        return [
            'user_id' => $this->_user_id,
            'message' => $this->_message,
            'type' => 'message_chat',
            'path' => $this->_path,
            'count' => $_count,
            'message_id' => $this->_message_id,
            'created_at' => Carbon::now()->format('Y-m-d H:i')
        ];
    }

}
