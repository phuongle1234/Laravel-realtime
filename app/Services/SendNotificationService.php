<?php

namespace App\Services;

use App\Enums\EStatus;
use App\Repositories\UserRepository;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class SendNotificationService
{
  private $_collection;
  private $_role;

  public function __construct(UserRepository $userRepo)
  {
    $this->userRepo = $userRepo;
  }

  public function handle($_colection)
  {

    $this->_collection = $_colection;
    $this->getRole();

    $_user = $this->userRepo->fetchWhere(['status' => EStatus::ACTIVE])->with('settings')->whereIn('role', $this->_role)->get()->chunk(20);

    foreach($_user as $_key => $_value)
    {

      foreach($_value as $row)
      {

          $_seting = $row->setings;

          $_flag = isset($_seting->notifications_from_admin) ? $_seting->notifications_from_admin : true;

          if($_flag)
          {

            try{
              Notification::send($row, new SendNotification((object)[ 'title' => $this->_collection->title , 'content' => $this->_collection->content ] , null, $_colection->id ));
            }catch (\Throwable $e){
              Log::error([ "schedule send notification, email: {$row->email}, user_id: {$row->id}" , $e ]);
              continue;
            }

          }

      }

    }

    $_colection->status = 'sent';
    $_colection->save();

  }

  private function getRole(){

      switch( $this->_collection->destination ){
        //,'admin'
          case 'all': $this->_role = ['teacher','student']; break;
          case 'teacher': $this->_role = ['teacher']; break;
          case 'student': $this->_role = ['student']; break;
      }
      return $this->_role;

  }

}
