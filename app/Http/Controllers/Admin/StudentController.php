<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EStripe;
use App\Events\SendMailRemoveAccountEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Enums\EUser;

use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Stripe\Subscription;
use Throwable;


class StudentController extends Controller
{
	private $_userRepo;
	private $_order_by;

	const URL_INDEX = "admin.studentManagement.list";
	const PATH_INDEX = "pages.admin.student_management";

    public function __construct(UserRepository $userRepo)
	{
        Stripe::setApiKey(env('STRIPE_SECRET'));
		$this->_userRepo = $userRepo;
		$this->_userRepo->model->prefix = 'admin-student';
		parent::__construct();
	}

	public function index(Request $request)
	{
		try {
			$item = $this->_userRepo->fetchWhere(['role' => EUser::STUDENT, 'status' => EUser::STATUS_ACTIVE])
									->with('tickets')->withCount('request')
									->search($request->only('code','name','email','plan_id'))
									->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
									->paginate( $this->_limit );

			return view(self::PATH_INDEX.'.list',compact('item'));

		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function show(Request $request, $id)
	{
		try {
			$item = $this->_userRepo->fetchWhere([ 'id' => $id, 'role' => EUser::STUDENT, 'status' => EUser::STATUS_ACTIVE])
									->with('stripe')
									->withCount('request')->first();
			return view(self::PATH_INDEX.'.edit',compact('item'));
		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function destroy(Request $request)
	{
		try {
			$user = $this->_userRepo->fetchWhere([ 'id' => $request->id, 'role' => EUser::STUDENT ])->first();
            $user_stripe = $user->stripe()->first();

            if($user_stripe->plan_id !== EStripe::FREE_PLAN_ID){
                try {
                    // delete current subscription
                    $deleted_subscription = Subscription::retrieve($user_stripe->subscription_id,[])->cancel();
                }catch(ApiErrorException $e){}
            }

            try {
                // delete current customer
                $deleted_customer = Customer::retrieve($user_stripe->cus_id,[])->delete();
            }catch(ApiErrorException $e){}

            // update user status and remove all related data
            $user->status = EUser::STATUS_DELETED;
            $user->save();

            $user_stripe->delete();
            $user->tickets()->delete();

            // send mail
            $send_mail_data = [
                'name' => $user->name,
                'email' => $user->email
            ];

            event(new SendMailRemoveAccountEvent($send_mail_data));

			$user->delete();

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);
		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}
}
