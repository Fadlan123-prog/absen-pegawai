<?php

use App\models\User;
use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\LoginController;
Use App\Http\Controllers\DashboardController;
Use App\Http\Controllers\UserController;
Use App\Http\Controllers\AttendanceController;

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

Auth::routes();

Route::get('/', [LoginController::class, 'gate'])->name('login.gate');
Route::get('login', [LoginController::class, 'index'])->name('login.index');
Route::post('login', [LoginController::class, 'post'])->name('login.post');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

//Admin routes
Route::group(['middleware' => ['role:admin']], function () {
    
    Route::get('user', [UserController::class, 'index'])->name('user.index');
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user/create', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::patch('user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('user/{id}', [UserController::class, 'delete'])->name('user.delete');
});

//User Routes
Route::group(['middleware' => ['role:user']], function(){
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [AttendanceController::class, 'distance'])->name('attendance.distance');
    Route::post('attendance/create', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
});
   
 Route::get('logout', [LoginController::class, 'logout'])->name('logout.index');
