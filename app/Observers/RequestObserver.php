<?php

namespace App\Observers;

use App\Enums\EStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\URL;
use App\Models\Request;
use App\Enums\ENotification;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use App\Services\RequestService;
use App\Models\Contact;
use App\Models\User;
use Log;
use Mail;

use function PHPUnit\Framework\returnSelf;

class RequestObserver implements ShouldQueue
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
    public function created(Request $_request)
    {
        try {

            // SEND REQUEST DIRECT
            if($_user_id = $_request->user_receive_id){

                $_user = User::find($_user_id);
                $_student = $_request->student()->first();

                Notification::send($_user, new SendNotification( (object)ENotification::SEND_REQUEST_DIRECT( $_student->name, $_request->deadline ), $_request->id) );
            }

        } catch (\Throwable $th) {
            Log::error("event send notification \n". $th->getMessage() ."\n");
        }
    }

      /**
     * Handle the user "updated" event.
     *
     * @param  \App\Request  $_request
     * @return void
     */

    public function updated(Request $_request)
    {

        try {

            $_value_change = $_request->getChanges();

            if( !isset($_value_change['status']) )
                return false;

            if($_value_change['status'] == EStatus::ACCEPTED){
                //RequestService::handle($_request);

                $_user = User::find($_request->user_id);
                $_teacher = $_request->teacher()->first();

                Notification::send($_user, new SendNotification( (object)ENotification::ACCEPT_REQUEST( $_request->title, $_teacher->name ) ) );
            }

            if($_value_change['status'] == EStatus::PASS){
                // $_user_receive = User::find($_request->user_receive_id );
                $_user = User::find($_request->user_id );
                Notification::send($_user, new SendNotification( (object)ENotification::PASS_VIDEO_TO_STUDENT($_request->title) ) );
                // Notification::send($_user_receive, new SendNotication( (object)ENotification::ACCEPT_VIDEO) );
            }

            if( $_value_change['status'] == EStatus::COMPLETE ){
                Contact::where('request_id',$_request->id)->update(['status' => EStatus::DENIED]);
            }


        } catch (\Throwable $th) {
            report("event handle request \n".$th."\n");
        }
    }

}
