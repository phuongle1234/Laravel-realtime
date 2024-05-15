<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Events\SendMailRegisterComplete;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;


class SendMailCompleteListen implements ShouldQueue
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
    public function handle(SendMailRegisterComplete $event)
    {
        try{

            $subject = "Edutoss アカウント審査完了のお知らせ";

            $details = $event->data;

            $mailTo = $details->email;

            Mail::send("mail.register.complete",compact('details'),
                    function($e) use ($mailTo, $subject) {
                        $e->to($mailTo)
                        ->subject($subject);
                    }
            );

        } catch (\Throwable $th) {

            Log::error(['send mail register complete listen', $th ]);
        }
    }
}
