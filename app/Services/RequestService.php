<?php

namespace App\Services;

use App\Enums\EStatus;
use App\Enums\EUser;
use App\Enums\ETransfer;
use App\Models\UserTransfer;
use App\Repositories\VerifyUserRepository;
use App\Repositories\UserRepository;

use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNotification;
use App\Enums\ENotification;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Request;
use Log;
use DB;


class RequestService
{


//   public function __construct(VerifyUserRepository $_repo, UserRepository $_repoUser, Request $request)
//   {
//     Stripe::setApiKey(env('STRIPE_SECRET'));
//     $this->_repo = $_repo;
//     $this->_repoUser = $_repoUser;
//     $this->request = $request;
//   }

  public static function handle($_request)
  {
    try {

        DB::beginTransaction();

            $_message = Message::create();

            // create contact
            Contact::insert([
                ['user_id' => $_request->user_id,'user_contact_id' => $_request->user_receive_id,'request_id' => $_request->id,'message_id' => $_message->id,'status' => EStatus::ACTIVE,'created_at' => Carbon::now()],
                ['user_id'=> $_request->user_receive_id,'user_contact_id' => $_request->user_id,'request_id' => $_request->id,'message_id' => $_message->id,'status' => EStatus::ACTIVE,'created_at' => Carbon::now()],
            ]);

        DB::commit();

    } catch (\Throwable $th) {
        DB::rollBack();
        report("request Service \n".$th->getMessage()."\n");
    }
  }


  //Request
  public static function refund($_request)
  {
    try {

        DB::beginTransaction();

            $_request->delete();

            $_tickets = $_request->tickets();
            $_tickets->update([ 'note' => "request id :{$_request->id} has been deleted" ]);
            Notification::send( $_request->student , new SendNotification( (object) ENotification::EXPIRED_REQUEST($_request->title) ) );

            $_tickets->delete();

        DB::commit();

    } catch (\Throwable $th) {
        DB::rollBack();
        report("schecle refund \n".$th->getMessage()."\n");
    }
  }

    // Update request urgent
    public static function updateRequestUrgent()
    {
        try {

            DB::beginTransaction();

            $_requests = Request::where([
                'status' => EStatus::PENDING
            ])->get();


            foreach($_requests as $_request){

                if( EUser::checkUrgent( $_request->created_at ) ){
                    $_request->is_urgent = 1;
                    $_request->save();
                }

            }

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            report("schedule update request urgent \n".$th."\n");
        }
    }

    public static function handleComplete($_request)
    {
        try {

        DB::beginTransaction();

            $_transfer = ETransfer::NORMAL_REQUEST;

            if($_request->is_direct === 1) $_transfer = ETransfer::DIRECT_REQUEST;
            if($_request->is_urgent === 1) $_transfer = ETransfer::URGENT_REQUEST;

            $teacher = $_request->teacher()->first();
            $total_current_reward = $teacher->reward + $_transfer;

            // update request status
            $_request->status = EStatus::COMPLETE;
            $_request->save();

            // update transfer
            UserTransfer::create([
                'user_id' => $_request->user_receive_id,
                'amount' => $_transfer,
                'current_reward' => $total_current_reward,
                'status' => EStatus::ACTIVE,
                'note' => 'cong tien tu complete request',
                'action' => ETransfer::COMPLETE_REQUEST
            ]);

            Notification::send( $_request->teacher , new SendNotification( (object) ENotification::COMPLETE_REQUEST( $_request->title ) ) );

            //Contact::where('request_id',$_request->id)->update(['status' => 'denied']);

        DB::commit();

            } catch (\Throwable $th) {

                DB::rollBack();
                report("update Complete for request \n".$th."\n");

            }

        return $_request;
    }

    // Update request complete

    // public static function updateRequestComplete()
    // {
    //     try {

    //         DB::beginTransaction();

    //         $_now = Carbon::now()->format('Y-m-d H:i:s');

    //         $_requests = Request::where('status',EStatus::PASS)->where('deadline','<',$_now)->get();

    //         foreach($_requests as $_request){
    //             // $deadline = Carbon::createFromFormat('Y-m-d H:i:s',$_request->deadline);
    //             // if($deadline->diffInDays($_now) > 2){
    //                 self::handleComplete($_request);
    //             // }
    //         }

    //         DB::commit();

    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         report("schedule update request complete \n".$th."\n");
    //     }
    // }

    // Update request is late
    public static function updateRequestIsLate($_request)
    {
        try {

            DB::beginTransaction();
                $_request->status = EStatus::DELAYED;
                $_request->save();
                Notification::send( $_request->teacher , new SendNotification( (object) ENotification::DELAYED_REQUEST($_request->title) ) );
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            report("schedule request is late deadline \n".$th."\n");
        }

    }

    public static function updatePassVideo($_request)
    {
        try {

            DB::beginTransaction();

                $_request->status = EStatus::PASS;
                $_request->deadline = EUser::getDeadLine( $_request->status );
                //Carbon::now( )->addDay( EUser::STUDENT_COMFROM )->format('Y-m-d H:i');
                $_request->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            report("schedule request pass failse \n".$th."\n");
        }

    }
}
