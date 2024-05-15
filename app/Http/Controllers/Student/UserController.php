<?php

namespace App\Http\Controllers\Student;

use App\Enums\EStatus;
use App\Http\Requests\ContactRequest;
use App\Services\Student\SettingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Enums\EUser;
use App\Requests\Student\ChangeRegistRequest;

use App\Enums\EStripe;
use Auth;
use Hash;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Contact;
use Illuminate\Support\Facades\Storage;
use App\Repositories\RequestRepository;

use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNotification;
use App\Enums\ENotification;

use Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Image;
use Throwable;

use Stripe\Subscription;
use App\Traits\StoreFileTrait;
use Illuminate\Support\Str;



class UserController extends Controller
{

    use StoreFileTrait;

    private $_userRepo;
    private $_request_repo;

    private $_setting_service;

    const URL_INDEX = "student.";
    const PATH_INDEX = "pages.student.home";

    public function __construct(UserRepository $userRepo, RequestRepository $_request_repo, SettingService $service)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->_userRepo = $userRepo;
        $this->_request_repo = $_request_repo;

        $this->_setting_service = $service;

        parent::__construct();
        // $this->driver = 'public';
    }

    public function index(Request $request)
    {
        try {

            return view(self::PATH_INDEX . '.index');
        } catch (Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function showCardId(Request $request)
    {
        try {
            $_user = Auth::guard('student')->user();
            $_stripe = $_user->stripe()->first();

            $_card = Customer::allSources(
                $_stripe->cus_id,
                ['object' => 'card', 'limit' => 100]
            )->data;

            $_default = Customer::retrieve($_stripe->cus_id, [])->default_source;
            if ($request->method() == 'GET') {
                return view('pages.student.setting.payment', compact('_card', '_default'));
            }

            return view('component.pop_up.payment_method', compact('_card', '_default'));
        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function addCard(Request $request)
    {
        try {
            $_user = Auth::guard('student')->user();
            $_stripe = $_user->stripe()->first();

            Customer::createSource(
                $_stripe->cus_id,
                ['source' => $request->token_stripe]
            );


            return redirect()->route(self::URL_INDEX . "setting.payment");
        } catch (Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function updateDefaultCard(Request $request)
    {
        try {

            DB::beginTransaction();

                $_user = Auth::guard('student')->user();

                $_stripe = $_user->stripe()->first();
                $_stripe->card_id = $request->card_id;
                $_stripe->save();

                Customer::update(
                    $_stripe->cus_id,
                    ['default_source' => $request->card_id]
                );

            DB::commit();

            return redirect()->route(self::URL_INDEX . "setting.payment");
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function showEditCard(Request $request, $card_id)
    {
        try {
            $_user = Auth::guard('student')->user();
            $_stripe = $_user->stripe()->first();

            $_card = Customer::retrieveSource(
                $_stripe->cus_id,
                $card_id
            );

            return view("pages.student.setting.payment_edit", compact('_card'));
        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function cardInfor(Request $request)
    {
        try {
            $_user = Auth::guard('student')->user();
            $_stripe = $_user->stripe()->first();

            $_card = Customer::retrieveSource(
                $_stripe->cus_id,
                $request->card_id
            );

            return view("component.pop_up.edit_card", compact('_card'));
        } catch (\Throwable $e) {
            report($e);
            return response()->json($e, 400);
        }
    }

    // using ajx
    public function updateCardInfor(Request $request)
    {
        try {
            $_user = Auth::guard('student')->user();
            $_stripe = $_user->stripe()->first();

            Customer::deleteSource(
                $_stripe->cus_id,
                $request->card_id,
                []
            );

            Customer::createSource(
                $_stripe->cus_id,
                ['source' => $request->token_stripe]
            );

            return response()->json(trans('common.success'), 200);
        } catch (Throwable $e) {
            report($e);
            return response()->json($e, 400);
        }
    }

    public function paymentIntents(Request $request)
    {
        try {
            DB::beginTransaction();

            $_user = Auth::guard('student')->user();
            $_stripe = $_user->stripe()->first();
            $total_amount = $request->tickets * EStripe::TICKETS_PRICE_UNIT;
            $_payment = Charge::create([
                'amount' => $total_amount,
                'currency' => 'JPY',
                'customer' => $_stripe->cus_id,
                'source' => $_stripe->card_id
            ]);

            $_user->tickets()->create(
                [
                    'amount' => $request->tickets,
                    'status' => EStatus::ACTIVE,
                    'note' => 'buy ticket by stripe',
                    'action' => EUser::BUY_TICKET_ACTION
                ]
            );

            DB::commit();

            return response()->json(['tickets' => $_user->fresh()->ticket], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return response()->json($e, 400);
        }
    }

    public function updateCard(Request $request, $card_id)
    {
        try {
            $_user = Auth::guard('student')->user();
            $_stripe = $_user->stripe()->first();

            Customer::deleteSource(
                $_stripe->cus_id,
                $card_id,
                []
            );

            Customer::createSource(
                $_stripe->cus_id,
                ['source' => $request->token_stripe]
            );

            return redirect()->route(self::URL_INDEX . "setting.payment");
        } catch (Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $item = $this->_userRepo->fetchWhere(['id' => $id, 'status' => EUser::STATUS_ACTIVE])->first();
            return view(self::PATH_INDEX . '.edit', compact('item'));
        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function update(ChangeRegistRequest $request)
    {
        try {

            $_user = Auth::guard('student')->user();
            $_request = $request->only('email', 'name');

            if ($request->avatar) {
                //Image

                if ($_user->avatar) {
                    //Storage::disk('public')->delete($_user->avatar);
                    $this->storageDelete($_user->avatar);
                }

                $_avatar = $this->storageUpload($request->avatar, 'avatar', $_user->code."-". date('Ymdhis') . ".png" );

                // $_avatar = Storage::disk('public')->putFileAs(
                //     'avatar',
                //     $request->avatar,
                //     $_user->code . "." . $request->avatar->getClientOriginalExtension()
                // );

                // $img = Image::make("storage/" . $_avatar);

                // $img->resize(150, null, function ($constraint) {
                //     $constraint->aspectRatio();
                // })->save();

                $_request['avatar'] = $_avatar;
            }

            if( $request->password )
                $_request['password'] = Hash::make( $request->password );

            $_user->update($_request);

            return redirect()->back()->with(['message' => trans("common.success")]);
        } catch (Throwable $e) {
            report($e);
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function updateNoti(Request $request)
    {
        try {
            $_user = Auth::guard('student')->user();
            $_seting = $_user->settings();

            $_seting->updateOrCreate(
                ['user_id' => $_user->id],
                [
                    'notifications_by_email' => $request->notifications_by_email ? true : false,
                    'notifications_from_admin' => $request->notifications_from_admin ? true : false,
                    'other_notices' => $request->other_notices ? true : false
                ]
            );

            return redirect()->back()->with(['message' => trans("common.success")]);
        } catch (Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function message(Request $request)
    {
        try {

            $_user = Auth::guard('student')->user();
            $_contact = Contact::where(['user_id' => $_user->id])
                        ->with(['request', 'user', 'message'])
                        ->whereHas('user', function($_query){
							$_query->whereNull('users.deleted_at');
						})
                        ->whereHas('request', function($_query){
							$_query->whereNull('requests.deleted_at');
						})
                        ->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )->get();

            return view('pages.student.message.index', compact('_contact'));
        } catch (Throwable $th) {
            report($th->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function contact(ContactRequest $request)
    {
        if($request->isMethod('POST')){
            try {
                $subject = "【自動送信メール】Webよりお問い合わせがありました";
                $mailTo = env('MAIL_CONTACT');


                $details = $request->all();

                Mail::send(
                    "mail.inquiry", compact('details'),
                    function ($e) use ($mailTo, $subject) {
                        $e->to($mailTo)
                            ->subject($subject);
                    }
                );

                return redirect()->back()->with(['message' => trans("common.success.inquiry")]);
            } catch (\Throwable $th) {
                report($th->getMessage());
                return redirect()->back()->withErrors(trans("common.error"));
            }
        }

        $list_request_complete = auth()->guard('student')->user()->list_request_complte;

        return view('pages.student.setting.inquiry',compact(
            'list_request_complete'
        ));

    }

    public function blockRequest(Request $request)
    {
        try {

            DB::beginTransaction();

            $_user = Auth::guard('student')->user();
            $_contact = Contact::where('id', $request->contact_id)->first();
            $_contact->status = EStatus::DENIED;
            $_contact->save();

            $_request = $this->_request_repo
                        ->fetchWhere(['id' => $_contact->request_id])
                        ->whereNotIn('status',[EStatus::APPROVED,EStatus::COMPLETE])
                        ->with('teacher')->first();

            if( ! $_request)
				throw new ErrorException( trans("common.error") );

            $_request->delete();

            $_tickets = $_request->tickets();
            $_tickets->update([ 'note' => "request id :{$_request->id} has been blocked by student" ]);
            $_tickets->delete();

            $_user->blockedUser()->updateOrCreate(
                ['user_id' => $_user->id, 'blocked_user_id' => $_contact->user_contact_id],
                ['blocked_user_id' => $_contact->user_contact_id]
            );

            Notification::send( $_request->teacher , new SendNotification( (object) ENotification::BLOCKED_REQUEST($_user->name) ) );

            DB::commit();
            return redirect()->back()->with(['message' => trans("common.success")]);
        } catch (Throwable $th) {
            DB::rollBack();

            Log::error($th);
            // report($th);
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function showPlan()
    {
        try {

        $_user = auth()->guard('student')->user();

        $_user_stripe = $_user->stripe()->first();

        if( $_user_stripe->plan_id != EStripe::FREE_PLAN_ID )
        {
            $_stripe_info = Subscription::retrieve($_user_stripe->subscription_id ,[]);
            $_user->subscription = $_stripe_info;

        }

        return view('pages.student.setting.plan_change',
                    [
                      '_user' => $_user, '_user_stripe' => $_user_stripe,
                      '_period_end' => isset(  $_stripe_info ) ?  $_stripe_info->cancel_at_period_end : true
                    ]
                );

        } catch (Throwable $th) {
            report($th->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function showPlanPayment()
    {
        try {

            $_user = auth()->guard('student')->user();
            $_user_stripe = $_user->stripe()->first();

            $_card = Customer::allSources(
                $_user_stripe->cus_id,
                ['object' => 'card', 'limit' => 100]
            )->data;

            $_confirm_plan_text = "登録を完了いたします。<br>よろしいでしょうか。";
            if ($_user_stripe->plan_id !== EStripe::FREE_PLAN_ID) {
                $_confirm_plan_text = "プランの変更は次回のお支払日<br>{$_user->current_period_end}に行われます。";
            }

            return view('pages.student.setting.plan_payment', compact('_user', '_card', '_confirm_plan_text'));
        } catch (Throwable $th) {
            report($th->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function updatePlan()
    {
        try {
            $response = $this->_setting_service->updatePlan();
            return response()->json(['message' => $response['message']],$response['code']);
        } catch (Throwable $exception){
            report($exception->getMessage());
            return response()->json(['message' => trans('common.error')]);
        }
    }

    public function showCancel(Request $request)
    {
        if($request->wantsJson()){
            try{
                $response = $this->_setting_service->cancelPlan();
                return response()->json(['message' => $response['message']],$response['code']);
            } catch(Throwable $exception){
                report($exception->getMessage());
                return response()->json(['message' => trans('common.error')]);
            }
        }

        $_user = auth()->guard('student')->user();
        $_user_stripe = $_user->stripe()->first();

        if($_user->stripe()->first()->plan_id === EStripe::FREE_PLAN_ID) abort(404);

        $_stripe_info = Subscription::retrieve($_user_stripe->subscription_id ,[]);

        $_user->subscription = $_stripe_info;
        return view('pages.student.setting.cancel_procedure', compact('_user'));
    }
}
