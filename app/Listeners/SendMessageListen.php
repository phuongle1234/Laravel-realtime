<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\MessageSentEvent;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;

use App\Models\Contact;

class SendMessageListen implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */

    // private $_file_path;

    // public function __construct()
    // {
    //    $this->_file_path = '';
    // }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(MessageSentEvent $event)
    {
        try{

            $_message = Message::find($event->_message_id);

            // if( $event->_path )
            //     $this->_file_path = FileManage::upload($event->_path);

            if( !( $_content = json_decode($_message->content,true) ) )
                $_content = [];

            array_push($_content,[ 'user_id' => $event->_user_id, 'message' => $event->_message, 'path' => $event->_path, 'created_at' => Carbon::now()->format('Y-m-d H:i') ] );

            $_content = json_encode($_content);
            $_message->content = $_content;
            $_message->save();

        } catch (\Throwable $th) {
            report("Request from listen insert message \n = " . $th  . "\n");
        }
    }
}
