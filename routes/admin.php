<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

//use Illuminate\Support\Facades\Crypt;

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


Route::get('/', [AuthController::class, 'index'])->name('index');

// login for admin
Route::get('/login', function () {
    return view('pages.login.admin');
})->name('login');
Route::post('/login', [AuthController::class, 'loginAdmin'])->name('hand_login');

Route::middleware(['auth:admin'])->group(function () {

    //  admin management
    Route::controller(UserController::class)->prefix('admin_management')->as('adminManagement.')->group(function () {
        Route::get('/list', 'index')->name('list');
        Route::get('/edit/{id}', 'show')->name('edit');
        Route::put('/edit/{id}', 'update')->name('handel_edit');
    });

    //  student management
    Route::controller(StudentController::class)->prefix('student_management')->as('studentManagement.')->group(function () {
        Route::match(['GET', 'POST'], '/list', 'index')->name('list');
        Route::get('/edit/{id}', 'show')->name('edit');
        Route::delete('/delete', 'destroy')->name('delete');
    });

    //  lecturer cp management
    Route::controller(LecturerCPController::class)->prefix('lecturer_cp_management')->as('lecturerCPManagement.')->group(function () {
        Route::match(['GET', 'POST'], '/list', 'index')->name('list');
        Route::get('/edit/{id}', 'show')->name('edit');
        Route::put('/edit/{id}', 'update')->name('update');
    });

    //  teacher management

    Route::controller(TeacherController::class)->prefix('lecturer_management')->as('teacherManagement.')->group(function () {

        Route::match(['GET', 'POST'], '/list', 'index')->name('list');
        Route::match(['GET', 'POST'], '/list_approve', 'indexApprove')->name('list_approve_status');
        Route::patch('/list_approve', 'unlock')->name('unlock');

        Route::get('/edit_approve/{id}', 'showApprove')->name('edit_approve');
        Route::put('/edit_approve/{id}', 'updateAccount')->name('update_account');

        Route::get('/edit/{id}', 'show')->name('edit');
        Route::put('/edit/{id}', 'update')->name('update');

        Route::delete('/delete', 'destroy')->name('delete');
        Route::patch('/list', 'storeStatus')->name('update_status');
    });

    //  notification_managemen
    Route::controller(NotificationManagementController::class)->prefix('notification_management')->as('notificationManagement.')->group(function () {
        Route::get('/list', 'index')->name('list');
        Route::get('/edit/{id}', 'show')->name('edit');
        Route::put('/edit/{id}', 'update')->name('update');

        Route::get('/add', 'create')->name('add');
        Route::post('/add', 'store')->name('handelAdd');
        Route::post('/update-notification/','updateNotification')->name('notification_update');
        Route::delete('/delete', 'destroy')->name('delete');
    });

    //  notification_delivery
    Route::controller(NotificationDeliveryController::class)->prefix('notification_delivery')->as('notificationDelivery.')->group(function () {
        Route::match(['GET', 'POST'], '/list', 'index')->name('list');
        Route::get('/edit/{id}', 'show')->name('edit');
        Route::put('/edit/{id}', 'update')->name('update');

        Route::get('/add', function () {
            return view('pages.admin.notification_delivery.add');
        })->name('add');
        Route::post('/add', 'store')->name('handelAdd');
        Route::delete('/delete', 'destroy')->name('delete');
    });

    //  tag_management
    Route::controller(TagManagementController::class)->prefix('tag_management')->as('tagManagement.')->group(function () {
        Route::match(['GET', 'POST'], '/list', 'index')->name('list');

        Route::group(['prefix' => 'field', 'as' => 'field.'], function () {
            Route::get('/add', function () {
                return view('pages.admin.tag_management.field.add');
            })->name('add');
            Route::post('/add', 'store')->name('handelAdd');

            Route::get('/edit/{id}', 'show')->name('edit');
            Route::post('/edit/{id}', 'update')->name('update');
        });

        Route::group(['prefix' => 'difficult', 'as' => 'difficult.'], function () {
            Route::get('/add', function () {
                return view('pages.admin.tag_management.difficult.add');
            })->name('add');
            Route::post('/add', 'store')->name('handelAdd');

            Route::get('/edit/{id}', 'show')->name('edit');
            Route::post('/edit/{id}', 'update')->name('update');
        });

        Route::patch('/list', 'storeStatus')->name('update_status');
        Route::delete('/delete', 'destroy')->name('delete');
    });

    Route::controller(RequestManagementController::class)->prefix('request_management')->as('request_management.')->group(function () {
        Route::match(['GET','POST'],'/', 'list')->name('list');
        Route::get('/detail/{id}', 'show')->name('detail');
        Route::post('/update/{id}','update')->name('update');
        Route::delete('/delete', 'destroy')->name('delete');

    });

    Route::controller(VideoController::class)->prefix('video_management')->as('video_management.')->group(function () {
        Route::match(['GET', 'POST'], '/list', 'listAll')->name('list');
        Route::get('detail/{id}','detail')->name('detail');
        Route::put('detail/{id}','update')->name('update');
        Route::delete('/delete', 'destroy')->name('delete');

        Route::get('/list_approval', 'list')->name('list_approval');

        Route::match(['GET', 'POST'],'/views', 'views')->name('list_views');
        Route::put('/views', 'exportCsvView')->name('export_csv_view');
        // ajx
        Route::put('/review', 'review')->name('review');
        Route::put('/denied', 'denied')->name('denied');
        Route::put('/pass', 'pass')->name('pass');
        Route::put('/active', 'active')->name('active');
    });

    Route::controller(CompensationController::class)->prefix('compensation_management')->as('compensation_management.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::post('/export', 'export')->name('export');
        Route::match(['GET','POST'],'/history','history')->name('history');
        Route::get('/{id}', 'show')->name('detail');
    });

    // Vimeo Management
    Route::controller(VimeoController::class)->prefix('vimeo_management')->as('vimeo_management.')->group(function () {
        Route::get('/', 'list')->name('list');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/update/{id}','update')->name('update');
    });
});

//  logout
// Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
