<?php

namespace App\Listeners;

use App\Events\SendMailActivatedTeacherAccount;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\MessageSentEvent;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class SendMailActivatedTeacherAccountListen implements ShouldQueue
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
    public function handle(SendMailActivatedTeacherAccount $event)
    {
        try{

            $subject = "【edutoss】アカウント認証完了のお知らせ";

            $details = (array) $event->data;
            $mailTo = $details['email'];

            Mail::send("mail.register.activated",compact('details'),
                    function($e) use ($mailTo, $subject) {
                        $e->to($mailTo)
                        ->subject($subject);
                    }
            );

        } catch (\Throwable $th) {

            Log::error(['send mail activated listen', $th ]);
        }
    }
}
