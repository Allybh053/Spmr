<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SmtpRelayController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();
//Login
Route::get('/login',[LoginController::class, 'index'])->name('login' );

Route::get('/logout', [LoginController::class, 'logout'])->name('logout' );

Route::group(['middleware' => ['auth']], function () {

    //testing route
    Route::get('/home', [DashboardController::class, 'index'])->name('dashboard.index');

	//Dashboard
    Route::group(['prefix' => 'users'], function () {
        Route::get('/usermanagements/create', [UsersController::class, 'create'])->name('usermanagements.create');
        Route::get('/usermanagements/index', [UsersController::class, 'index'])->name('usermanagements.index');
        Route::post('/usermanagements', [UsersController::class, 'store'])->name('usersmanagements.store');
        Route::get('/usermanagements/{id}/edit', [UsersController::class, 'edit'])->name('usersmanagements.edit');
        Route::get('/usermanagements/{id}', [UsersController::class, 'show'])->name('usersmanagements.show');
        Route::put('/usermanagements/{id}', [UsersController::class, 'update'])->name('usersmanagements.update');
        Route::delete('/usermanagements/{id}', [UsersController::class, 'destroy'])->name('usersmanagements.destroy');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/{id}/updatepassword', [ProfileController::class, 'updatepassword'])->name('profile.updatepassword');
        Route::post('/profile/{id}/updatedetail', [ProfileController::class, 'updatedetail'])->name('profile.updatedetail');

    });


    //SMTP Relay Request 
    Route::get('/smtp_relay/smtp_relay_req', [SmtpRelayController::class, 'smtp_relay_req'])->name('smtp_relay.smtp_relay_req');
    Route::get('/smtp_relay/form_application_smtp_relay', [SmtpRelayController::class, 'add'])->name('smtp_relay.form_application_smtp_relay');



});

