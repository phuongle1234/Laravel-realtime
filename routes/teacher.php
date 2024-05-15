<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// ->middleware('auth:teacher')

Route::get('/','UserController@index')->name('home')->middleware('auth:teacher');

Route::middleware(['auth:teacher','can:viewTeacher,App\Models\User'])->group(function (){

    // request
    Route::controller(RequestController::class)->prefix('request')->as('request.')->group(function(){
        Route::put('/handleRequest','handle')->name('handle');
        // ajax
        Route::put('/filter','index')->name('filter');
        Route::put('/review','review')->name('review');

        Route::get('/list', function(){  return view('pages.teacher.request.index');  })->name('list');

        Route::get('/accepted', function(){  return view('pages.teacher.request.accepted');  })->name('accepted');

        Route::get('/accepted/{id}','accepted')->name('accepted_request');
        Route::put('/accepted/{id}','handelAccept')->name('handel_accept');

        Route::get('/edit/{id}','edit')->name('edit');
        Route::put('/edit/{id}','handleEdit')->name('handle_edit');

    });

    // video

    Route::controller(VideoController::class)->prefix('video')->as('video.')->group(function(){

        Route::get('/list', 'index')->name('list');
        Route::put('/list','store')->name('add');
        // delete
        Route::patch('/delete','delete')->name('delete');
        //
        Route::patch('/update','update')->name('update');
    });


    // notification
    Route::group(['as' => 'notification.'], function(){
        Route::get('/notification/{id}', function( $id ){  return view('pages.teacher.notification.index', compact('id'));  })->name('show');
        Route::get('/notification', function( ){  return view('pages.teacher.notification.index');  })->name('index');
    });

    // message
    Route::get('/message','UserController@message')->name('message');


    Route::controller(SettingController::class)->prefix('setting')->as('setting.')->group(function(){
        Route::get('/',function(){  return view('pages.teacher.setting.index');  })->name('index');
        Route::match(['get','post'],'/profile','profile')->name('profile');
        Route::match(['get', 'post'],'/notification','notification')->name('notification');
        Route::match(['get', 'post'],'/request_reception','request_reception')->name('request_reception');
        Route::match(['get', 'post'],'/account_info','accountInfo')->name('account_info');
        Route::match(['GET','POST'],'/inquiry','contact')->name('inquiry');

        Route::get('/user_guide',function(){return view('pages.teacher.setting.user_guide');})->name('user_guide');
        Route::get('/faq',function(){return view('pages.teacher.setting.faq');})->name('faq');
        Route::get('/privacy_policy',function(){return view('pages.teacher.setting.privacy_policy');})->name('privacy_policy');
        Route::get('/law',function(){return view('pages.teacher.setting.law');})->name('law');


    });

    // reward

    Route::controller(RewardController::class)->prefix('reward')->as('reward.')->group(function(){
        Route::match(['GET','POST'], '/','index')->name('index');
        Route::match(['GET','POST'],'/history/','history')->name('history');
    });


});

// ajax
Route::put('/updatePercent','UserController@updatePercent')->name('update_percent');

