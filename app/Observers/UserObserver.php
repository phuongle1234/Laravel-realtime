<?php

namespace App\Observers;

use App\Enums\EStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Log;
use Mail;

class UserObserver implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;
    // public function __construct(LogService $logService)
    // {
    //     $this->logService = $logService;
    // }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $_user)
    {
        try {

             $_user->settings()->create([
                'notifications_by_email' => 1,
                'notifications_from_admin' => 1,
                'other_notices' => 1,
            ]);

        } catch (\Throwable $th) {
            report("event create setting when user created \n".$th."\n");
        }
    }

//    public function updated(VerifyUser $_verifyUser)
//    {
//
//        try {
//
//            // send mail re-otp
//            $_value_change = $_verifyUser->getChanges();
//
//            if($_verifyUser->status == 'non_verified' && $_verifyUser->step == 0)
//            {
//                $subject = "Edutoss プログラム仮登録のお知らせ";
//                $_view = "mail.register.otp";
//            }
//
//            // send mail complete
//            if($_verifyUser->status == EStatus::COMPLETE && $_verifyUser->user_id != null &&isset($_value_change['user_id']) )
//            {
//                $subject = "Edutoss アカウント審査完了のお知らせ";
//                $_view = "mail.register.complete";
//            }
//
//            // send mail reset pass
//            if($_verifyUser->status == 'reset_pass' && isset($_value_change['token']) )
//            {
//                $subject = "Edutoss パスワード再設定";
//                $_view = "mail.register.reset_password";
//            }
//
//            if(!isset($subject) && !isset($_view))
//            return true;
//
//                //register.
//                $details = $_verifyUser->toArray();
//                $mailTo = $_verifyUser->email;
//                Mail::send($_view,compact('details'),
//                        function($e) use ($mailTo, $subject) {
//                            $e->to($mailTo)
//                            ->subject($subject);
//                        }
//                );
//
//
//        } catch (\Throwable $th) {
//            report("event send mail otp \n".$th."\n");
//        }
//    }

}
