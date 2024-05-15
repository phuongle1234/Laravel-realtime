<?php

namespace App\Policies;

use App\Enums\EStatus;
use App\Enums\EStripe;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Services\VerifyUserService;

class UserPolicy
{
    use HandlesAuthorization;

    private $_service;
    private $_request;

    public function __construct(VerifyUserService $_service, Request $_request)
	{
		$this->_service = $_service;
        $this->_request = $_request;
	}

    // public function before(User $_user, $ability)
    // {

    //     //if ($_user->role == "student"){
    //         return false;
    //     //}
    // }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $manager
     * @return mixed
     */

     public function sendRequestDirect(User $_user)
    {

        $_service = $this->_service;
        $_service->user = $_user;
        return $_service->checkTeacher() ;
    }

     public function sendRequest(User $_user)
    {
        if($_user->ticket)
            return true;
    }

    public function viewTeacher(User $_user)
    {
        //$_verifyUser = $_user->verifyUser()->first();
        // if( isset($_verifyUser) && $_verifyUser->unlock )
        if( $_user->status == EStatus::ACTIVE )
            return true;
    }

    public function buyPoint(User $_user)
    {
        $_stripe = $_user->stripe()->first();

        if( isset($_stripe) && $_stripe->plan_id > 1 && $_stripe->plan_id < EStripe::PREMIUM_PLAN_ID )
            return true;
    }

    public function viewStudent(User $_user)
    {

        $_stripe = $_user->stripe()->first();

        if( isset($_stripe) && $_stripe->plan_id > 1 )
        return true;

    }

}
