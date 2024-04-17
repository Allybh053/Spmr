<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LdapController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\Saml2Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomainMinistryAgenciesListController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LogOverviewController;
use App\Http\Controllers\LoopBackController;
use App\Http\Controllers\MailLogController;
use App\Http\Controllers\MainRelayController;
use App\Http\Controllers\PositionDepartmentController;
use App\Http\Controllers\PrintListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReplicationController;
use App\Http\Controllers\ReportDetailSenderController;
use App\Http\Controllers\ReportQueryLogController;
use App\Http\Controllers\ReportStatisticController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\Setup\RoleController;
use App\Http\Controllers\SmtpRelayController;
use App\Http\Controllers\SystemInformationController;
use App\Http\Controllers\UcOverviewController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\AuthResource;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageServiceController;
use App\Http\Controllers\GenerateReportingController;


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
    Route::get('/getServer', [DashboardController::class, 'api'])->name('dashboard.api');
    Route::get('/home/{limit}',[DashboardController::class, 'limit'])->name('dashboard.limit');

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
        Route::get('/', [MainRelayController::class, 'index'])->name('mailrelay.index')->middleware('auth.resource:manage_mail_relay_view');
        Route::get('/register', [MainRelayController::class, 'show'])->name('mailrelay.register');
        Route::get('/detail/{id}', [MainRelayController::class, 'view','connect'])->name('mailrelay.edit');
        Route::get('/reject/{id}', [MainRelayController::class, 'reject'])->name('mailrelay.reject');

        // Route::get('/detail/{id}', [MainRelayController::class, 'updatestatus']);
    });

    //SMTP Relay Request
    Route::group(['prefix' => 'smtp_relay'], function () {
        Route::get('/smtp_relay_req', [SmtpRelayController::class, 'index'])->name('smtp_relay.smtp_relay_req')->middleware('auth.resource:smtp_mail_relay_view');
        Route::get('/form_application_smtp_relay', [SmtpRelayController::class, 'create'])->name('smtp_relay.create');
        Route::post('/form_application_smtp_relay/add', [SmtpRelayController::class, 'store'])->name('smtp_relay.store');
        Route::get('/form_application_smtp_relay/edit/{id}', [SmtpRelayController::class, 'edit'])->name('smtp_relay.edit');
        Route::post('/form_application_smtp_relay/update/{id}', [SmtpRelayController::class, 'update'])->name('smtp_relay.update');
        Route::post('/form_application_smtp_relay/delete/{id}', [SmtpRelayController::class, 'delete'])->name('smtp_relay.delete');
        Route::post('/form_application_smtp_relay/datatable', [SmtpRelayController::class, 'datatable'])->name('smtp_relay.datatable');

        //code sidi
        Route::get('/register', [SmtpRelayController::class, 'daftar'])->name('smtp_relay.daftar');
        Route::post('/saving', [SmtpRelayController::class, 'saving'])->name('smtp_relay.saving');
        Route::get('/view/{id}', [SmtpRelayController::class, 'papar'])->name('smtp_relay.view');
        Route::get('/update/{id}', [SmtpRelayController::class, 'kemaskini'])->name('smtp_relay.kemaskini');
        Route::post('/update/{id}/kemaskinidata', [SmtpRelayController::class, 'kemaskinidata'])->name('smtp_relay.kemaskinidata');


        Route::get('/index/searching', [SmtpRelayController::class, 'search'])->name('smtp_relay.index.search');

    });

    Route::group(['prefix' => 'server'], function () {
        Route::get('/server', [ServerController::class, 'index'])->name('server.index')->middleware('auth.resource:server_overview_view');
        Route::get('/create', [ServerController::class, 'create'])->name('server.create');
        Route::get('/edit/{id}', [ServerController::class, 'show'])->name('server.edit');
        Route::post('/update-status', [ServerController::class, 'updateStatus']);
        Route::post('/upload-server', [ServerController::class, 'uploadServer'])->name('upload-server');
        Route::post('/update/{id}', [ServerController::class, 'update'])->name('server.update');

        Route::get('/{id}/file/download', [ServerController::class, 'downloadFile'])->name('server.file.download');
        Route::post('/servers/{id}', [ServerController::class, 'deleteServer'])->name('server.delete');

    });

    Route::group(['prefix' => 'history_connection_mail_relay'], function () {
        Route::get('/blacklist_firewall', [HistoryController::class, 'indexBlacklist'])->name('history.blacklist.index');
        Route::post('/blacklist_firewall/datatable', [HistoryController::class, 'datatableBlacklist'])->name('history.blacklist.datatable');
        Route::post('/blacklist_firewall/search', [HistoryController::class, 'searching'])->name('history.blacklist.search');
        Route::post('/blacklist_firewall/search/datatable', [HistoryController::class, 'datatableSearching'])->name('history.blacklist.datatable.search');

        Route::get('/whitelist_firewall', [HistoryController::class, 'indexWhitelist'])->name('history.whitelist.index');
        Route::post('/whitelist_firewall/datatable', [HistoryController::class, 'datatableWhitelist'])->name('history.whitelist.datatable');

        Route::get('/fail_to_ban_firewall', [HistoryController::class, 'indexFail'])->name('history.fail.index');
        Route::post('/fail_to_ban_firewall/datatable', [HistoryController::class, 'datatableFail'])->name('history.fail.datatable');
    });


    Route::group(['prefix' => 'uc_overview'], function () {
        Route::get('/', [UcOverviewController::class, 'indexApplication'])->name('uc.index');
        Route::post('/datatable', [UcOverviewController::class, 'datatableApplication'])->name('uc.datatable');
    });


    Route::group(['prefix' => 'log_overview'], function () {
        Route::get('/', [LogOverviewController::class, 'index'])->name('log.index')->middleware('auth.resource:spmr_activity_log_view');
        Route::post('/api/getServerName', [LogOverviewController::class, 'getNewServerData'])->name('log.api.getServerName');

    });

    Route::group(['prefix' => 'mail_log_overview'], function () {
        Route::get('/', [MailLogController::class, 'index'])->name('mailLog.index')->middleware('auth.resource:mail_log_view');
            Route::post('/validation', [MailLogController::class, 'validation'])->name('mailLog.validation');
    });

    Route::group(['prefix' => 'report'], function () {
        Route::group(['prefix' => 'statistic-summary'], function () {
            Route::get('/', [ReportStatisticController::class, 'index'])->name('report.statistic.index')->middleware('auth.resource:traffic_statistic_summary_view');
            Route::post('/validation', [ReportStatisticController::class, 'validation'])->name('report.statistic.validation');
        });

        Route::group(['prefix' => 'detail-sender'], function () {
            Route::get('/', [ReportDetailSenderController::class, 'index'])->name('report.detail.index')->middleware('auth.resource:detail_report_sender_view');
            Route::post('/validation', [ReportDetailSenderController::class, 'validation'])->name('report.detail.validation');
        });

        Route::group(['prefix' => 'query-log'], function () {
            Route::get('/', [ReportQueryLogController::class, 'index'])
            ->name('report.queryLog.index')
            ->middleware('auth.resource:query_log_view');
            Route::post('/validation', [ReportQueryLogController::class, 'validation'])->name('report.queryLog.validation');
        });
    });

    		//role
		Route::group(['prefix' => 'role'], function () {
			Route::get('/',[RoleController::class,'index'])
            ->name('setup.role.index')
            ->middleware('auth.resource:role_permission_view');
			Route::post('/add',[RoleController::class,'create'])->name('setup.role.create');
			Route::post('/delete',[RoleController::class,'delete'])->name('setup.role.delete');
			Route::post('/storePermission',[RoleController::class,'storePermission'])->name('setup.role.storePermission');
		});

    Route::group(['prefix' => 'domain'], function () {
        Route::get('/', [DomainMinistryAgenciesListController::class, 'index'])
        ->name('domain.index')
        ->middleware('auth.resource:domain_name_view');
        Route::get('/register', [DomainMinistryAgenciesListController::class, 'create'])->name('domain.register');
        Route::post('/register', [DomainMinistryAgenciesListController::class, 'store'])->name('domain.store');
        Route::get('/{id}', [DomainMinistryAgenciesListController::class, 'edit'])->name('domain.edit');
        Route::post('/update/{id}', [DomainMinistryAgenciesListController::class, 'update'])->name('domain.update');
        Route::post('/delete/{id}', [DomainMinistryAgenciesListController::class, 'delete'])->name('domain.delete');
    });

    Route::group(['prefix' => 'systeminformation'], function () {
        Route::get('/', [SystemInformationController::class, 'index'])->name('systeminformation.index');
    });

    Route::group(['prefix' => 'positiondepartment'], function () {
        Route::get('/', [PositionDepartmentController::class, 'index'])
        ->name('positiondepartment.index')
        ->middleware('auth.resource:position_department_view');
        Route::get('/addposition', [PositionDepartmentController::class, 'addposition'])->name('positiondepartment.addposition');
        Route::get('/adddepartment', [PositionDepartmentController::class, 'adddepartment'])->name('positiondepartment.adddepartment');
        Route::get('/updateposition/{id}', [PositionDepartmentController::class, 'updateposition'])->name('positiondepartment.updateposition');
        Route::get('/updatedepartment/{id}', [PositionDepartmentController::class, 'updatedepartment'])->name('positiondepartment.updatedepartment');
        Route::put('/saveupdateposition/{id}', [PositionDepartmentController::class, 'saveupdateposition'])->name('positiondepartment.saveupdateposition');
        Route::put('/saveupdatedepartment/{id}', [PositionDepartmentController::class, 'saveupdatedepartment'])->name('positiondepartment.saveupdatedepartment');
        Route::post('/saveposition', [PositionDepartmentController::class, 'savingposition'])->name('positiondepartment.saveposition');
        Route::post('/savedepartment', [PositionDepartmentController::class, 'savingdepartment'])->name('positiondepartment.savedepartment');
        Route::delete('/department/{id}/departmentdelete', [PositionDepartmentController::class, 'departmentdelete'])->name('positiondepartment.departmentdelete');
        Route::delete('/position/{id}/positiondelete', [PositionDepartmentController::class, 'positiondelete'])->name('positiondepartment.positiondelete');

    });

    Route::group(['prefix' => 'validation-verification'], function () {
        Route::get('/loopback', [LoopBackController::class, 'index'])
                ->name('loopback.index')
                ->middleware('auth.resource:loopback_smtp_test_view');
        Route::get('/replication', [ReplicationController::class, 'index'])
                ->name('replication.index')
                ->middleware('auth.resource:replication_status_view');
        Route::get('/printList', [PrintListController::class, 'index'])
                ->name('printList.index')
                ->middleware('auth.resource:print_list_ip_address_view');
        Route::post('/printList/validation', [PrintListController::class, 'validation'])->name('printList.validation');
    });


    Route::group(['prefix' => 'manage-service'], function () {
        Route::get('/{server_id?}/{server_type?}', [ManageServiceController::class, 'index'])->name('manageService.index')
        ->middleware('auth.resource:manage_service_view');
    });

    Route::group(['prefix' => 'generate-report'], function () {
        Route::get('/', [GenerateReportingController::class, 'index'])->name('generateReport.index')
        ->middleware('auth.resource:generate_report_view');
        Route::get('/getServer', [GenerateReportingController::class, 'api'])->name('generateReport.api');
        Route::get('/index/searching', [GenerateReportingController::class, 'search'])->name('generateReport.index.search');
    });


    Route::get('/activity-logs', [ActivityLogController::class,'showActivityLogs'])->name('activityLog.index')
      ->middleware('auth.resource:spmr_activity_log_view');

});

