<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;


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

Route::middleware(['auth:student'])->group(function () {

    Route::get('/', 'UserController@index')->name('home');
    // notification

    Route::group(['as' => 'notification.'], function () {
        Route::get('/notification/{id}', function ($id) {
            return view('pages.student.notification.index', compact('id'));
        })->name('show');
        Route::get('/notification', function () {
            return view('pages.student.notification.index');
        })->name('index');
    });
    // message
    Route::get('/message', 'UserController@message')->name('message');
    //Route::post('/message','UserController@ajaxGetMessage')->name('ajax_get_message');

    // faq
    Route::controller(UserController::class)->prefix('faq')->as('faq.')->group(function () {
        Route::get('/questions', function () {
            return view('pages.student.faq.question');
        })->name('question');
        Route::get('/user_guide', function () {
            return view('pages.student.faq.user_guide');
        })->name('user_guide');
        Route::get('/policy', function () {
            return view('pages.student.faq.policy');
        })->name('policy'); //
        Route::get('/law', function () {
            return view('pages.student.faq.law');
        })->name('law');
    });

    // teacher list
    Route::controller(TeacherController::class)->prefix('teacher_list')->as('teacher.')->group(function () {
        Route::get('/', function () {
            return view('pages.student.teacher_list.index');
        })->name('index');


        Route::post('/teacher', 'index')->name('list');
        //->middleware('can:viewStudent,App\Models\User');

        Route::get('/infor/{id}', 'info' )->name('infor');

        Route::post('/teacherRequest', 'teacherRequest')->name('old_request');
    });

    // request
    Route::controller(RequestController::class)->prefix('request')->as('request.')->group(function () {
        //Route::get('/',function(){  return view('pages.student.teacher_list.index');  })->name('index');
        Route::get('/', function () {
            return view('pages.student.request.index');
        })->name('list');
        Route::get('/create', function () {
            return view('pages.student.request.create');
        })->name('create');

        Route::get('/create', function () {
            return view('pages.student.request.create');
        })->name('create');

        Route::get('/create_directly/{id}', function ($id) {
            return view('pages.student.request.create_direct');
        })->name('create_direct')->middleware('can:sendRequestDirect,App\Models\User');

        //handle request sendRequest

        Route::put('/handle_request', 'handle')->name('handle')->middleware('can:sendRequest,App\Models\User');
        //ajax review
        Route::post('/review', 'review')->name('review');

        Route::put('/confirm', 'confirm')->name('confirm');
    });

     // video
     Route::controller(VideoController::class)->prefix('video')->as('video.')->group(function () {
            Route::get('/', function () {
                return view('pages.student.video.index');
            })->name('list');

            // filter video
            Route::put('/filter','index')->name('index');
            //->middleware('can:viewStudent,App\Models\User');
     });

    //study_management
    Route::controller(UserController::class)->prefix('study')->as('study.')->group(function () {
        Route::match(['GET', 'POST'],'/', function () { return view('pages.student.study.index'); })->name('index');
    });

    // setting
    Route::controller(UserController::class)->prefix('setting')->as('setting.')->group(function () {
        Route::get('/', function () {
            return view('pages.student.setting.index');
        })->name('index');
        Route::get('/change_regist', function () {
            return view('pages.student.setting.change_regist');
        })->name('regist');
        Route::put('/change_regist', 'update')->name('handle_regist');
        Route::get('/notification', function () {
            return view('pages.student.setting.notification');
        })->name('notification');
        Route::put('/notification', 'updateNoti')->name('handle_noti');
        // Payment
        // POST using for ajax
        Route::match(['GET', 'POST'], '/payment', 'showCardId')->name('payment');

        Route::get('/payment_info_setting', function () {
            return view('pages.student.setting.payment_info_setting');
        })->name('payment_info');
        Route::put('/payment_info_setting', 'addCard')->name('handle_payment_info');
        Route::patch('/payment', 'updateDefaultCard')->name('update_default');

        Route::get('/payment_info_edit/{card_id}', 'showEditCard')->name('payment_edit');
        // AJAX GET CARD ID
        Route::post('/cardInfor', 'cardInfor')->name('card_info');
        Route::put('/updateCardInfor', 'updateCardInfor')->name('update_card_info');
        Route::patch('/PaymentIntents', 'PaymentIntents')->name('Payment_intent')->middleware('can:viewStudent,App\Models\User');

        Route::put('/payment_info_edit/{card_id}', 'updateCard')->name('handle_payment_edit');
        //inquiry
        Route::match(['GET','POST'],'/inquiry','contact')->name('inquiry');
        // block request
        Route::put('/blockRequest', 'blockRequest')->name('block_request');

        // plan change
        Route::get('/plan-change','showPlan')->name('plan_change');
        Route::get('/plan-payment','showPlanPayment')->name('plan_payment');

        // ajax update plan
        Route::post('/update-plan','updatePlan')->name('update_plan');

        // cancel procedure
        Route::match(['GET','POST'],'/cancel-procedure','showCancel')->name('cancel');
    });

});
