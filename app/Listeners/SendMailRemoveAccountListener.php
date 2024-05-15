<?php

namespace App\Listeners;

use App\Events\SendMailCancelPlanEvent;
use App\Events\SendMailPaymentFailedStripeEvent;
use App\Events\SendMailRemoveAccountEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMailRemoveAccountListener implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SendMailRemoveAccountEvent $event)
    {
        $data = $event->data;

        try {

            $subject = "【edutoss】アカウント停止のお知らせ";
            $mailTo = $data['email'];

            Mail::send("mail.remove_account",compact('data'),
                function($e) use ($mailTo, $subject) {
                    $e->to($mailTo)
                        ->subject($subject);
                }
            );

        }catch (\Throwable $e){
            report($e->getMessage());
        }
    }
}
