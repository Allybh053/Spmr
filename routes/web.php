<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\Saml2Controller;
use App\Http\Controllers\Auth\LdapController;


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
// SAML2 login routes
Route::get('/saml/login', [Saml2Controller::class, 'samlLogin'])->name('saml.login');
Route::post('/saml/acs', [Saml2Controller::class, 'samlAcs'])->name('saml.acs');

// LDAP login routes
Route::get('/ldap/login', [LdapController::class, 'showLoginForm'])->name('ldap.login');
Route::post('/ldap/login', [LdapController::class, 'ldapLogin'])->name('ldap.login.submit');

// TOTP login routes
Route::get('/totp/login', [LoginController::class, 'show2faForm'])->name('2fa.show');
Route::post('/totp/login', [LoginController::class, 'verify2fa'])->name('2fa.verify');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout' );




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

