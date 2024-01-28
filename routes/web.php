<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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
Route::get('/login','Auth\LoginController@index')->name('login' );

Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );

Route::group(['middleware' => ['auth']], function () {

    //testing route
    Route::get('/main', [DashboardController::class, 'index'])->name('dashboard.index');

	//Dashboard
	Route::group(['prefix' => 'users'], function () {
        //testing route
        Route::get('/user', function () {
            return view('users/usermanagements/create');
        });
        //testing route
        Route::get('/profile', function () {
            return view('users/profile/index');
        });

        // Route::get('/', [UserController::class, 'index'])->name('user.index');
        // Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');

    });



});

