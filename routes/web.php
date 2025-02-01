<?php

use App\Http\Controllers\Blocked_Days\BlockedDayController;
use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Departments\DepartmentController;
use \App\Http\Controllers\Leaves\LeaveController;
use \App\Http\Controllers\Overtimes\OvertimeController;
use \App\Http\Controllers\Holidays\HolidayController;
use Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\Notifications\NotificationController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

// Disable the default logout route
Auth::routes(['logout' => false]);

Route::group(['controller' => EmployeeController::class, 'as' => 'employees.'], function () {
    Route::post('/logout', 'logout')->name('logout');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::group(['prefix' => 'employees', 'middleware' => 'role_custom:employee|human_resource|sg|head'], function () {
        Route::get('/index', 'index')->name('index');
        Route::get('/show/{employee}', 'show')->name('show');
        Route::get('/editpassword/{employee}', 'editPassword')->name('editPassword');
        Route::put('/updatepassword/{employee}', 'updatePassword')->name('updatePassword');
    });
    Route::group(['prefix' => 'employees', 'middleware' => 'role_custom:human_resource|sg|head'], function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/editprofile/{employee}', 'editProfile')->name('editProfile');
        Route::put('/updateprofile/{employee}', 'updateProfile')->name('updateProfile');
        Route::delete('/{employee}', 'destroy')->name('destroy');
    });
});

Route::group(['middleware' => 'role_custom:human_resource|sg|head', 'controller' => DepartmentController::class, 'prefix' => 'departments', 'as' => 'departments.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{department}', 'edit')->name('edit');
    Route::put('/update/{department}', 'update')->name('update');
    Route::get('/{department}', 'show')->name('show');
    Route::delete('/{department}', 'destroy')->name('destroy');
    Route::get('/', 'index')->name('index');
});


Route::group(['middleware' => 'role_custom:employee|human_resource|sg|head', 'controller' => LeaveController::class, 'prefix' => 'leaves', 'as' => 'leaves.'], function () {
    Route::get('/acceptedIndex', 'acceptedIndex')->name('acceptedIndex');
    Route::get('/rejectedIndex', 'rejectedIndex')->name('rejectedIndex');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/submitted', 'submitted')->name('submitted');
    Route::post('/destroy/{leave}', 'destroy')->name('destroy');
    Route::get('/download/{leave}', 'downloadAttachment')->name('downloadAttachment');
    Route::post('/accept/{leave}', 'accept')->name('accept');
    Route::post('/reject/{leave}', 'reject')->name('reject');
    Route::get('/createReport', 'createReport')->name('createReport');
    Route::post('/generateReport', 'generateReport')->name('generateReport');
    Route::get('/{leave}/show', 'show')->name('show');
    Route::group(['prefix' => '/calendar'], function () {
        Route::get('/generate', 'generateCalendar')->name('generateCalendar');
    });
    Route::get('/index', 'index')->name('index');
});

Route::group(['middleware' => 'role_custom:employee|human_resource|sg|head', 'controller' => OvertimeController::class, 'prefix' => 'overtimes', 'as' => 'overtimes.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/submitted', 'submitted')->name('submitted');
    Route::post('/destroy/{overtime}', 'destroy')->name('destroy');
    Route::post('/accept/{overtime}', 'accept')->name('accept');
    Route::post('/reject/{overtime}', 'reject')->name('reject');
    Route::get('/acceptedIndex', 'acceptedIndex')->name('acceptedIndex');
    Route::get('/rejectedIndex', 'rejectedIndex')->name('rejectedIndex');
    Route::get('/createReport', 'createReport')->name('createReport');
    Route::post('/generateReport', 'generateReport')->name('generateReport');
    Route::get('/{overtime}', 'show')->name('show');
    Route::get('/', 'index')->name('index');
});

Route::group(['middleware' => 'role_custom:human_resource|sg|head', 'controller' => HolidayController::class, 'prefix' => 'holidays', 'as' => 'holidays.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{holiday}', 'edit')->name('edit');
    Route::put('/update/{holiday}', 'update')->name('update');
    Route::get('/{holiday}', 'show')->name('show');
    Route::delete('/{holiday}', 'destroy')->name('destroy');
    Route::get('/', 'index')->name('index');
});

Route::group(['middleware' => 'role_custom:human_resource|sg|head', 'controller' => BlockedDayController::class, 'prefix' => 'blocked-days', 'as' => 'blocked-days.'], function () {
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{blocked_day}', 'edit')->name('edit');
    Route::put('/update/{blocked_day}', 'update')->name('update');
    Route::get('/{blocked_day}', 'show')->name('show');
    Route::delete('/{blocked_day}', 'destroy')->name('destroy');
    Route::get('/', 'index')->name('index');
});

Route::group(['middleware' => 'role_custom:human_resource', 'controller' => NotificationController::class, 'prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('/tabbed_view', 'tabbed_view')->name('tabbed_view');
    Route::post('/store', 'store')->name('store');
    Route::get('/show/{notificationsGroup}', 'show')->name('show');
    Route::delete('/delete/{notificationsGroup}', 'destroy')->name('destroy');
});

Route::group(['middleware' => 'role_custom:employee|human_resource|sg|head', 'controller' => \App\Http\Controllers\HolidaysAndConfessionnels\HolidaysAndConfessionnels::class, 'prefix' => 'holidays-and-confessionnels', 'as' => 'holidays-and-confessionnels.'], function () {
    Route::get('/index', 'index')->name('index');
});

Route::any('{url}', function () {
    return back();
})->where('url', '.*');
