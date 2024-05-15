<?php

use App\Http\Controllers\WebHookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
// use App\Requests\Teacher\ProfileTeacherRequest;
use Illuminate\Http\Request;
// use Illuminate\Validation\Validator;

// use Carbon\Carbon;
// use App\Enums\EUser;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// test checkUrgent

Route::get('/', [AuthController::class,'index'] )->name('home');

// login for student or teacher
Route::get('/login_app', function(){return view('pages.login.app'); } )->name('loginMyApp');
Route::post('/login_app', [AuthController::class,'login'] )->name('handle_login');

//forgotpass
Route::get('/forgot_pass', function(){ return view('pages.reset_password.forgot_pass'); } )->name('forgotPass');
Route::post('/forgot_pass', [AuthController::class,'forgotPass'] )->name('handleForgotPass');

//reset password
Route::group(['middleware'=>'CheckToken', 'as'=>'reset_pass.' ], function() {
    Route::get('/reset_password/{token}', function(){ return view('pages.reset_password.reset_pass'); } )->name('index');
    Route::put('/reset_password/{token}', [AuthController::class,'resetPassword'] )->name('handle');
});

// URL DOWLOAD
Route::get('/linkDowload/{name}',[EventController::class,'linkDowload'])->name('link_dowload');

//  logout
Route::get('/logout/{role}', [AuthController::class,'logout'] )->name('logout');

//  route event for ajax

// Route::get('/videoTest',[EventController::class,'videoToken'])->name('video_token');
Route::post('/checkName', function(Request $request){

    $validator = Validator::make($request->all(), [ 'name' => 'required|string|max:40|unique:users,name' ]);

    $validator->setAttributeNames([ 'name' => 'ユーザー名' ]);

     if ($validator->fails())
    return response()->json(['error' => $validator->errors()], 400);

    return response()->json( true , 200);

})->name('check_name');

Route::group(['as' => 'ajax.' ],function(){

    Route::post('/getMessage',[EventController::class,'getMessage'])->name('load_message');
    Route::post('/sendMessage',[EventController::class,'chat'])->name('chat');
    Route::post('/seenMessage',[EventController::class,'eventSeen'])->name('seen');

    Route::put('/checkRequest',[EventController::class,'checkRequest'])->name('check_request');
//videoHandle
    Route::put('/tokenHandle',[EventController::class,'videoToken'])->name('video_token');

    Route::put('/userLike',[EventController::class,'userLike'])->name('user_like');
    // get school master
    Route::put('/getUniversity',[EventController::class,'getUniversity'])->name('get_university');
    Route::put('/userView',[EventController::class,'userView'])->name('user_view');
    Route::put('/getFaculty',[EventController::class,'getFaculty'])->name('get_faculty');
    Route::put('/userStatistics',[EventController::class,'userStatistics'])->name('user_statistics');

    Route::put('/getWarningBlock',[EventController::class,'getWarningBlock'])->name('get_Warning_block');

    Route::put('/getNumberMessage',[EventController::class,'getNumberMessage'])->name('get_number_message');
    // getNumberRequestDirect
    Route::put('/getNumberRequestDirect',[EventController::class,'getNumberRequestDirect'])->name('get_number_request_direct');

});

// page register
Route::group(['prefix'=>'additional_pages','as'=>'register.'], function(){
    Route::get('/newcreate',function(){ return view('pages.register.index'); })->name('index');
    Route::post('/newcreate', [AuthController::class,'store'] )->name('handleCreateNew');
    // otp
    Route::group(['middleware'=>'CheckToken'], function() {

        Route::get('/otp/{token}',function($token){
            // if(session('verify_user')->step == 0) return abort(404);
            return view('pages.register.otp');

        })->name('otp');

        Route::post('/otp/{token}',[AuthController::class,'checkOtp'])->name('otp');

        Route::get('/resend_otp/{token}',[AuthController::class,'resendToken'])->name('resend_otp');

        Route::get('/account_type/{token}',function($token){
            // if(session('verify_user')->step == 1) return abort(404);
            return view('pages.register.account_type');
        })->name('accountType');

        Route::patch('/account_type/{token}',[AuthController::class,'storeRole'])->name('hanldeRole');

        Route::group(['prefix'=>'fan_registration', 'as' => 'student.'], function(){
            Route::get('/{token}',function($token){
                // if(session('verify_user')->step == 2 && session('verify_user')->role == 'student')   return abort(404);
                return view('pages.register.student.profile');
            })->name('profile');
            Route::post('/{token}',[AuthController::class,'storeProfileStudent'])->name('hanldeProfile');

            Route::get('/payment/{token}',function($token){
                // if(session('verify_user')->step == 3 && session('verify_user')->role == 'student')
                // return abort(404);
                return view('pages.register.student.payment');   })->name('payment');
        });

        Route::group(['prefix'=>'teacher_registration' , 'as' => 'teacher.'], function(){
            Route::get('/{token}',function($token){
                // if(session('verify_user')->step == 2 && session('verify_user')->role == 'teacher')  return abort(404);
                return view('pages.register.teacher.profile');
            })->name('profile');
            Route::post('/{token}',[AuthController::class,'storeProfileTeacher'])->name('hanldeProfile');
        });

        //complete
        Route::match(['GET','POST'],'/complete/{token}',[AuthController::class,'complete'])->name('complete');
    });
});

$prefix = 'payment';
Route::group([
    'prefix' => $prefix
], function() use ($prefix) {
    $controllerName = WebHookController::class;
    $viewName = $prefix . '.';
    Route::post("/callback", [$controllerName, 'handleWebhook']);
});
