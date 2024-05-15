<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'CheckApi'], function(){
    Route::post('/fieldInfo', 'EventController@fieldInfo' );
    Route::post('/subjectInfo', 'EventController@subjectInfo' );
    Route::post('/field', 'EventController@field' );
    Route::post('/subject', 'EventController@subject' );
    Route::post('/video', 'VideoController@index' );
    Route::post('/video_list', 'VideoController@list' );
});

