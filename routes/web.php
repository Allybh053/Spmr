<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SmtpRelayController;
use App\Http\Controllers\MainRelayController;
use App\Http\Controllers\Auth\Saml2Controller;
use App\Http\Controllers\Auth\LdapController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\UcOverviewController;
use App\Http\Controllers\LogOverviewController;

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

    Route::group(['prefix' => 'mailrelay'], function () {
        Route::get('/', [MainRelayController::class, 'index'])->name('mailrelay.index');
        Route::get('/register', [MainRelayController::class, 'show'])->name('mailrelay.register');
        Route::get('/detail/{id}', [MainRelayController::class, 'view','connect'])->name('mailrelay.edit');
        // Route::get('/detail/{id}', [MainRelayController::class, 'updatestatus']);
    });

    //SMTP Relay Request
    Route::group(['prefix' => 'smtp_relay'], function () {
        Route::get('/smtp_relay_req', [SmtpRelayController::class, 'index'])->name('smtp_relay.smtp_relay_req');
        Route::get('/form_application_smtp_relay', [SmtpRelayController::class, 'create'])->name('smtp_relay.create');
        Route::post('/form_application_smtp_relay/add', [SmtpRelayController::class, 'store'])->name('smtp_relay.store');
        Route::get('/form_application_smtp_relay/edit/{id}', [SmtpRelayController::class, 'edit'])->name('smtp_relay.edit');
        Route::post('/form_application_smtp_relay/update/{id}', [SmtpRelayController::class, 'update'])->name('smtp_relay.update');
        Route::post('/form_application_smtp_relay/delete/{id}', [SmtpRelayController::class, 'delete'])->name('smtp_relay.delete');
        Route::post('/form_application_smtp_relay/datatable', [SmtpRelayController::class, 'datatable'])->name('smtp_relay.datatable');
    });


    Route::group(['prefix' => 'history_connection_mail_relay'], function () {
        Route::get('/blacklist_firewall', [HistoryController::class, 'indexBlacklist'])->name('history.blacklist.index');
        Route::post('/blacklist_firewall/datatable', [HistoryController::class, 'datatableBlacklist'])->name('history.blacklist.datatable');

        Route::get('/whitelist_firewall', [HistoryController::class, 'indexWhitelist'])->name('history.whitelist.index');
        Route::post('/whitelist_firewall/datatable', [HistoryController::class, 'datatableWhitelist'])->name('history.whitelist.datatable');

        Route::get('/fail_to_ban_firewall', [HistoryController::class, 'indexFail'])->name('history.fail.index');
        Route::post('/fail_to_ban_firewall/datatable', [HistoryController::class, 'datatableFail'])->name('history.fail.datatable');
    });


    Route::group(['prefix' => 'uc_overview'], function () {
        Route::get('/register_application', [UcOverviewController::class, 'indexApplication'])->name('uc.application.index');
        Route::post('/register_application/datatable', [UcOverviewController::class, 'datatableApplication'])->name('uc.application.datatable');

        Route::get('/register_domain', [UcOverviewController::class, 'indexDomain'])->name('uc.domain.index');
        Route::post('/register_domain/datatable', [UcOverviewController::class, 'datatableDomain'])->name('uc.domain.datatable');

        Route::get('/register_username', [UcOverviewController::class, 'indexUsername'])->name('uc.username.index');
        Route::post('/register_username/datatable', [UcOverviewController::class, 'datatableUsername'])->name('uc.username.datatable');

        Route::get('/wildcard', [UcOverviewController::class, 'indexWildcard'])->name('uc.wildcard.index');
        Route::post('/wildcard/datatable', [UcOverviewController::class, 'datatableWildcard'])->name('uc.wildcard.datatable');
    });


    Route::group(['prefix' => 'log_overview'], function () {
        Route::get('/', [LogOverviewController::class, 'index'])->name('log.index');
        Route::post('/api/getServerName', [LogOverviewController::class, 'getNewServerData'])->name('log.api.getServerName');
    });

});

