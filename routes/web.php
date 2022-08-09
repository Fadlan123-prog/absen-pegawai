<?php

use App\models\User;
use Illuminate\Support\Facades\Route;
Use App\Http\Controllers\LoginController;
Use App\Http\Controllers\DashboardController;
Use App\Http\Controllers\UserController;

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

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    Route::get('user', [UserController::class, 'index'])->name('user.index');
} );

