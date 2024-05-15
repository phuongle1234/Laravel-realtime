<?php

namespace App\Http\Controllers;

use App\Enums\EStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Hash;
use Str;

use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;
use App\Requests\ResetPassRequest;
use App\Enums\EPage;
use App\Enums\EUser;
use App\Services\VerifyUserService;
use App\Requests\Student\ProfileRequest;
use App\Requests\Teacher\ProfileTeacherRequest;
use App\Events\SendMailOtp;


use App\Models\User;

use Illuminate\Support\Facades\Log;
use App\Events\SendMailRegisterComplete;
use Illuminate\Support\Facades\RateLimiter;

use App\Events\SendMailRestPass;

use Exception;

class AuthController extends Controller
{
    //use VimeoApiTrait;

    private $_verifyService;
    const URL_INDEX = 'admin.login';
    const URL_APP = 'loginMyApp';
    const PATH_INDEX = 'pages.auth';

    const maxAttempts = 5;
    const decaySeconds  = 180;

    public function __construct(VerifyUserService $_verifyService)
    {
        $this->_verifyService = $_verifyService;
    }

    public function loginView()
    {
        return view(self::PATH_INDEX . ".login");
    }

    public function index(Request $request)
    {
        // check isset auth
        if (Auth::guard('admin')->check()) {
            return redirect()->route(EPage::redirectLogin('admin'));
        }
        if (Auth::guard('teacher')->check()) {
            return redirect()->route(EPage::redirectLogin('teacher'));
        }
        if (Auth::guard('student')->check()) {
            return redirect()->route(EPage::redirectLogin('student'));
        }


        //$request->route()

        return redirect()->route(self::URL_APP);
    }

    public function resetPassword(ResetPassRequest $request, $token)
    {
        try {

            $this->_verifyService->resetPassword($token);
            return redirect()->route(self::URL_APP)->with(['message' => trans("common.success.reset_complete")]);

        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function forgotPass(Request $request)
    {
        try {

            $_result =  $this->_verifyService->forgotPass();
            event( new SendMailRestPass($_result) );

            return redirect()->route(self::URL_APP)->with(['message' => trans("common.success.reset_pass")]);
        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function store(RegisterRequest $request)
    {
        try {

            $_result = $this->_verifyService->createOtp();
            event( new SendMailOtp( $_result ) );

            return redirect()->route('register.otp', ['token' => $_result->token]);

        } catch (Throwable $e) {
            report($e);
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function resendToken(Request $request, $token)
    {
        try {

            $_result = $this->_verifyService->repeatOtp($token);
            event( new SendMailOtp( $_result ) );

            return redirect()->route('register.otp', ['token' => $_result->token])->with(['message' => trans("common.success.otp_resend")]);
        } catch (Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    //verify_user
    public function checkOtp(Request $request, $token)
    {
        try {

            if ( $this->_verifyService->checkOtp($request->otp, $token) ) {
                return redirect()->route('register.accountType', ['token' => $token]);
            }

            return redirect()->back()->withErrors(trans("common.error.otp"));
        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function storeRole(Request $request, $token)
    {
        try {

            $_verify_user = (object) session('verify_user');

            if ($_verify_user->token == $token)
             return redirect()->route("register.{$request->role}.profile", ['token' => $token]);
                //$_verify_user->role = $request->role;



            //($_verify_user->step == 1) &&
            // {
            //     $_verify_user->role = $request->role;
            //     $_verify_user->step = 2;
            //     $_verify_user->save();
            //     $_verify_user->fresh();
            // }

            return redirect()->back()->withErrors(trans("common.error"));
        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function storeProfileStudent(ProfileRequest $request, $token)
    {
        try {

            $_verify_user = $this->_verifyService->storeProfile($token);
            // $_verify_user = (object)json_decode($_verify_user->value, true);

            if ((int)$_verify_user->plan == 1) {
                return redirect()->action([AuthController::class, EStatus::COMPLETE], [$token]);
            }

            return redirect()->route('register.student.payment', ['token' => $token]);

        } catch (\hrowable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    //ProfileTeacherRequest
    public function storeProfileTeacher(ProfileTeacherRequest $request, $token)
    {
        try {

            $_verify_user = $this->_verifyService->storeProfileTeacher($token);

            //event( new SendMailRegisterComplete( $_verify_user ) );

            $_token = $_verify_user->createToken('Token_' . $_verify_user->role )->plainTextToken;
            $_verify_user->access_token = $_token;
            $_verify_user->save();

            $_user = Auth::guard( $_verify_user->role )->loginUsingId( $_verify_user->id, false );

            //self::URL_APP

            return redirect()->route( $_verify_user->role.'.home' )->with(['message' => trans("common.success.regist")]);

        } catch (Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function complete(Request $request, $token)
    {
        try {

            $_verify_user = $this->_verifyService->createAccount($token);

            //event( new SendMailRegisterComplete( $_verify_user ) );

            $_token = $_verify_user->createToken('Token_' . $_verify_user->role)->plainTextToken;
            $_verify_user->access_token = $_token;
            $_verify_user->save();

            Auth::guard( $_verify_user->role )->loginUsingId( $_verify_user->id, false );

            if( $_verify_user->role == EUser::STUDENT )
            return view('pages.register.complete');

            return redirect()->route( $_verify_user->role.'.home' )->with(['message' => trans("common.success.regist")]);
            // return redirect()->route(self::URL_APP);
        } catch (Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function login(LoginRequest $request)
    {

        if (RateLimiter::tooManyAttempts($this->throttleKey(), self::maxAttempts))
                return abort(429);

        try {

            $_user =  User::where( 'email', $request->email)
                            ->whereNotIn('status', [ EUser::STATUS_DELETED ] )
                            ->whereIn('role',[ EUser::TEACHER, EUser::STUDENT  ])
                            ->first();

            if (!$_user)
                return redirect()->back()->withErrors( trans('common.error.password') );


            $_remember = isset($request->remember) ? true : false;



            if ( Auth::guard( $_user->role )->attempt(['email' => $request->email, 'password' => $request->password ], $_remember ) ){

                RateLimiter::clear($this->throttleKey());

                $_token = $_user->createToken('Token_' . $_user->role)->plainTextToken;
                $_user->access_token = $_token;
                $_user->save();


                if( $_remember )   //->cookie('_email_input', Crypt::encryptString($request->email) )
                return redirect()->route(EPage::redirectLogin($_user->role))->withCookie( \Cookie::forever('_email_input', Crypt::encryptString($request->email) ) );

                $cookie = \Cookie::forget('_email_input');
                return redirect()->route(EPage::redirectLogin($_user->role))->withCookie($cookie);
            }

            RateLimiter::hit($this->throttleKey(), self::decaySeconds);
            return redirect()->back()->withErrors( trans('common.error.password') );

        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function loginAdmin(LoginRequest $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), self::maxAttempts))
                return abort(429);

        try {
            if (Auth::guard(EUser::ADMIN)->attempt(
                [
                    'email' => $request->email,
                    'password' => $request->password,
                    'role' => 'admin',
                    'status' => EUser::STATUS_ACTIVE
                ]
            ))
            {
                RateLimiter::clear($this->throttleKey());
                $user = Auth::guard(EUser::ADMIN)->user();
                $token = $user->createToken('Token_'.EUser::ADMIN)->plainTextToken;
                $user->access_token = $token;
                $user->save();

                return redirect()->route(EPage::redirectLogin(EUser::ADMIN));
            }

            RateLimiter::hit($this->throttleKey(), self::decaySeconds);
            return redirect()->back()->withErrors("メールまたはパスワードが間違っています。");
        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function logout(Request $request, $role)
    {

        Auth::guard($role)->logout();

        if( $role == 'admin' )
         return redirect()->route(self::URL_INDEX);

        return redirect()->route(self::URL_APP);

        // if (Auth::guard('admin')->check()) {
        //     Auth::guard('admin')->logout();
        //     return redirect()->route(self::URL_INDEX);
        // }

        // if (Auth::guard('teacher')->check()) {
        //     Auth::guard('teacher')->logout();
        //     return redirect()->route(self::URL_APP);
        // }

        // if (Auth::guard('student')->check()) {
        //     Auth::guard('student')->logout();
        //     return redirect()->route(self::URL_APP);
        // }
    }

    public function throttleKey()
    {

        return Str::lower(request('email')) . '|' . request()->ip();
    }

}
