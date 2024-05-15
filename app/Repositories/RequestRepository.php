<?php

namespace App\Repositories;

use App\Enums\EPage;
use App\Models\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNotification;
use App\Enums\ENotification;

class RequestRepository extends BaseRepository
{
    public $model;

    public function __construct(Request $model)
    {
        parent::__construct($model);
    }

    public function getListRequests($conditions)
    {
        $result = $this->model->query();

        if (!empty($conditions['status'])) {
            $result = $result->where('status', $conditions);
        }

        return $result->whereHas('student')->paginate(EPage::E_PER_PAGE_DEFAULT);
    }

    public function delete($request_id)
    {
        $request = $this->model->find($request_id);
        $user = $request->student()->first();
        $teacher = $request->teacher()->first();

        // send noti
        Notification::send($user, new SendNotification((object)ENotification::DELETE_REQUEST($request->title) ));

        if( $teacher )
        Notification::send($teacher, new SendNotification((object)ENotification::DELETE_REQUEST($request->title) ));

        // delete ticket for refund
        $_tickets = $request->tickets();
        $_tickets->update([ 'note' => "request id :{$request->id} has been deleted from Admin" ]);
        $_tickets->delete();

        // delete request
        $request->delete();

    }
}
