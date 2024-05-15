<?php

namespace App\Services;

use App\Enums\EStatus;
use App\Repositories\VerifyUserRepository;
use App\Repositories\UserRepository;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\Subscription;
use Stripe\PaymentIntent;
use Stripe\paymentMethods;
use Hash;
use Illuminate\Support\Str;
use App\Enums\EStripe;
use DB;
use Exception;
use App\Enums\EUser;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Traits\RedisStorage;
use Image;
use App\Traits\StoreFileTrait;

class VerifyUserService
{
    use RedisStorage;
    use StoreFileTrait;

    public $_user;
    private $_repo;
    private $_repoUser;
    private $request;
    private $environment;

    // const s3_folder_path = "uploads/edutoss_dev/";

    public function __construct(VerifyUserRepository $_repo, UserRepository $_repoUser, Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $this->_repo = $_repo;
        $this->_repoUser = $_repoUser;
        $this->request = $request;
        $this->environment = env('STRIPE_ENVIRONMENT');
        // $this->driver = 'public';
    }

    public function createOtp()
    {
        $_token = Str::random(20);
        $_opt = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        $_expires_at = Carbon::now()->addDays(1)->format('Y-m-d H:i:s');

        $_value = [
            'email' => $this->request->email,
            'password' => Hash::make($this->request->password),
            'otp' => $_opt,
            'token' => $_token,
            'expires_at' => $_expires_at
        ];

        $this->setValue($_token, json_encode($_value));

        return (object)$_value;

        // return $this->_repo->store([
        //     'email' => $this->request->email,
        //     'password' => Hash::make($this->request->password),
        //     'otp' => $_opt,
        //     'token' => $_token,
        //     'expires_at' => $_expires_at,
        //     'status' => 'non_verified'
        // ]);

    }

    public function forgotPass()
    {
        $_user = $this->_repoUser->fetchWhere(['email' => $this->request->email])
            ->whereNotIn('status', [EUser::STATUS_DELETED])
            ->whereIn('role', [EUser::STUDENT, EUser::TEACHER])->first();

        if (!$_user) {
            throw new Exception(trans("common.error.email"));
        }

        // $_verify_user = $_user->verifyUser()->first();
        $_user->token = Str::random(20);
        // $_verify_user->status = 'reset_pass';
        $_user->save();

        return $_user->fresh();
    }

    public function resetPassword($token)
    {
        $_verify_user = session('verify_user');

        if ($_verify_user->token != $token) {
            throw new Exception(trans("common.error.token"));
        }


        // $_verify_user->status = 'complete';
        // $_verify_user->token = null;
        // $_verify_user->save();
        //user Hash
        // $_user = $_verify_user->user()->first();

        $_verify_user->password = Hash::make($this->request->password);
        $_verify_user->token = null;
        $_verify_user->save();
    }

    public function repeatOtp($token)
    {
        $_verify_user = (object)session('verify_user');

        if ($_verify_user->token != $token) {
            throw new Exception(trans("common.error.token"));
        }


        $_token = Str::random(20);
        $_opt = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        $_expires_at = Carbon::now()->addDays(1)->format('Y-m-d H:i:s');


        $_value = [
            'email' => $_verify_user->email,
            'password' => $_verify_user->password,
            'otp' => $_opt,
            'token' => $_token,
            'expires_at' => $_expires_at
        ];

        $this->delete($token);
        $this->setValue($_token, json_encode($_value));

        // $this->setValue( $_token,  json_encode($_value) );
        // return (object) $_value;

        // $_verify_user = session('verify_user');

        // if ($_verify_user->token == $token) {
        //     $_verify_user->token = $_token;
        //     $_verify_user->otp = $_opt;
        //     $_verify_user->expires_at = $_expires_at;
        //     $_verify_user->save();
        //     return $_verify_user->fresh();
        // }
        return (object)$_value;
    }

    public function checkOtp($otp, $token)
    {
        $_verify_user = (object)session('verify_user');

        if (($_verify_user->otp == $otp) && ($_verify_user->token == $token)) {
            return true;
        }
        // {
        //     $_verify_user->status = 'register';
        //     $_verify_user->step = 1;
        //     $_verify_user->save();

        //}

        return false;
    }

    public function checkTeacher()
    {
        $_id_teacher = $this->request->id;

        if (in_array($_id_teacher, $this->user->list_block)) {
            return false;
        }

        $_teacher = User::with('settings', 'subject')->where(['id' => $_id_teacher, 'role' => EUser::TEACHER])->first();

        if (!$_teacher) {
            return false;
        }

        if (!($_teacher->settings) || $_teacher->settings->accept_directly) {
            session(['teacher_infor' => $_teacher]);
            return true;
        }

        return false;
    }

    public function checkToken()
    {
        $_name_prefix = explode('.', $this->request->route()->getName())[0];

        if ($_name_prefix == 'reset_pass') {
            return User::where('token', $this->request->token)->first();
        }

        return json_decode($this->getValue($this->request->token), true);

        // $this->_repo->fetchWhere(['token' => $this->request->token])->whereDate(
        //     'expires_at',
        //     '>',
        //     Carbon::now()->format('Y-m-d H:i:s')
        // )->first();
    }

    public function storeProfileTeacher($token)
    {
        try {
            DB::beginTransaction();


            $_verify_user = (object)session('verify_user');

            if ($_verify_user->token != $token) {
                throw new Exception(trans("common.error.token"));
            }

            $this->delete($token);

            $_code = EUser::getCodeUser(EUser::TEACHER);

            //$_folder_vimeo = explode('/',$_folder_vimeo['body']['uri']);

            $_card_id = $this->storageUpload($this->request->card_id, 'card_id', $_code . ".png");

            // $_card_id = Storage::disk('public')->putFileAs(
            //     'card_id',
            //     $this->request->card_id,
            //     $_code . "." . $this->request->card_id->getClientOriginalExtension()
            // );

            $_avatar = null;

            if ($this->request->avatar) // $this->request->avatar->getClientOriginalExtension()
            {
                $_avatar = $this->storageUpload($this->request->avatar, 'avatar', $_code . ".png");
            }

            // $_avatar = Storage::disk('public')->putFileAs(
            //     'avatar',
            //     $this->request->avatar,
            //     $_code . "." . $this->request->avatar->getClientOriginalExtension()
            // );


            // create user

            $_account = User::create([
                'email' => $_verify_user->email,
                'password' => $_verify_user->password,
                'role' => EUser::TEACHER,
                'name' => $this->request->name,
                'kana' => $this->request->kana,
                'last_name' => $this->request->last_name,
                'birthday' => implode('-', $this->request->birthday),
                'avatar' => $_avatar,
                'sex' => $this->request->sex,
                'tel' => $this->request->tel,
                'code' => $_code,
                'status' => EUser::STATUS_PENDING,
                'university_Code' => $this->request->university_Code,
                'faculty_code' => $this->request->faculty_code,
                'edu_status' => $this->request->edu_status,
                'card_id' => $_card_id,
                'introduction' => $this->request->introduction
            ]);

            // craeta user subject
            $_subject = collect($this->request->subject)->map(function ($val) use ($_account) {
                return ['subject_id' => $val, 'user_id' => $_account->id];
            })->toArray();
            $_account->userSubject()->insert($_subject);

            //bank account
            $_bank_account = $_account->bankAccount();

            $_bank_account->updateOrCreate(
                $this->request->only(
                    'bank_code',
                    'branch_code',
                    'bank_account_number',
                    'bank_account_name',
                    'bank_account_type'
                )
            );


            // $_verify_user->value = json_encode(
            //     $this->request->only(
            //         'name',
            //         'last_name',
            //         'kana',
            //         'birthday',
            //         'gender',
            //         'education',
            //         'tel',
            //         'token_stripe'
            //     )
            // );

            // $_verify_user->user_id = $_account->id;
            // $_verify_user->card_id = $_card_id;
            // $_verify_user->undergraduate = $this->request->undergraduate;
            // $_verify_user->subject_study = $this->request->subject_study;
            // $_verify_user->introduction = $this->request->introduction;
            // $_verify_user->token = null;
            // $_verify_user->status = 'complete';
            // $_verify_user->step = 0;
            // $_verify_user->save();

            DB::commit();

            return $_account->fresh();
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            throw new Exception(trans("common.error"));
        }
    }

    public function storeProfile($token)
    {
        $_verify_user = (object)session('verify_user');

        //ProfileRequest
        if ($_verify_user->token != $token) {
            throw new Exception(trans("common.error.token"));
        }

        $_verify_user->name = $this->request->name;
        $_verify_user->plan = $this->request->plan;

        $this->setValue($token, json_encode((array)$_verify_user));

        // $_value = json_encode([
        //     'name' => $this->request->name,
        //     'plan' => $this->request->plan
        // ]);

        // if ($this->request->plan != 1) {
        //     $_step = 3;
        // }


        // $_verify_user->value = $_value;
        // $_verify_user->step = isset($_step) ? $_step : 0;
        // $_verify_user->save();

        return $_verify_user;
    }

    private function setPlanPrice($plan)
    {
        switch ($plan) {
            case EStripe::STANDARD_PLAN_ID:
                $plan_price = EStripe::STANDARD_MEMBER_PRICE_ID;
                break;
            case EStripe::PREMIUM_PLAN_ID:
                $plan_price = EStripe::PREMIUM_MEMBER_PRICE_ID;
                break;
        }
        return $plan_price;
    }

    public function createAccount($token)
    {
        try {
            //$request
            DB::beginTransaction();

            $_verify_user = (object)session('verify_user');

            if ($_verify_user->token != $token) {
                throw new Exception(trans("common.error.token"));
            }

            $this->delete($token);
            //$_value = (object)json_decode($_verify_user->value, true);


            // case EUser::STUDENT:

            if ($this->request->method() == 'POST') {
                $_verify_user->token_stripe = $this->request->token_stripe;
            }

            $_verify_user->plan = (int)$_verify_user->plan;

            $_customer = Customer::create([
                'name' => $_verify_user->name,
                'email' => $_verify_user->email,
            ]);

            if (!empty($_verify_user->token_stripe)) {
                $_card = Customer::createSource($_customer->id, [
                    'source' => $_verify_user->token_stripe
                ]);
            }

            if ($_verify_user->plan > 1) {
                $plan_price = $this->setPlanPrice($_verify_user->plan);

                $user_plan_subscription = Subscription::create([
                    'customer' => $_customer->id,
                    'items' => [
                        ['price' => $plan_price[$this->environment . '_stripe_price_id']]
                    ],
                    'payment_behavior' => 'allow_incomplete',
                    'proration_behavior' => 'always_invoice'
                ]);
            }

            $_account = User::create([
                'email' => $_verify_user->email,
                'password' => $_verify_user->password,
                'role' => EUser::STUDENT,
                'name' => $_verify_user->name,
                'code' => EUser::getCodeUser(EUser::STUDENT),
                'status' => EUser::STATUS_ACTIVE
            ]);

            $_account->stripe()->create([
                'cus_id' => isset($_customer) ? $_customer->id : null,
                'card_id' => isset($_card) ? $_card->id : null,
                'plan_id' => $_verify_user->plan,
                'price_id' => isset($plan_price) ? $plan_price[$this->environment . '_stripe_price_id'] : null,
                'subscription_id' => isset($user_plan_subscription) ? $user_plan_subscription->id : null
            ]);


            // bonus ticket if free plan
            if($_verify_user->plan === EStripe::FREE_PLAN_ID){
                $_account->tickets()->create([
                    'user_id' => $_account->id,
                    'amount' => EStripe::FREE_PLAN_BONUS_TICKET,
                    'status' => EStatus::ACTIVE,
                    'note' => 'moi tao account nen duoc bonus ticket',
                    'action' => EUser::BUY_TICKET_ACTION
                ]);
            }

            // $_verify_user->user_id = $_account->id;
            // $_verify_user->step = 0;
            // $_verify_user->status = 'complete';
            // $_verify_user->token = null;
            // $_verify_user->save();

            DB::commit();

            return $_account;
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e->getMessage());
        }
    }

}
