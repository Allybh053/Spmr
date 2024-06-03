<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Server;
use App\Models\RoleServer;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\RoleHasPermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run()
	{
		// Create Permissions
		$permissions = [
            "dashboard" => [
				'total_application_registered',
				'total_domain_registered',
				'number_of_registered_sasl',
				'total_ip_based_registered',
                'top_5_list_ministry',
                // 'top_5_list_department',
                'serial_mail_queue_chart',
                'view_current_mail_queue_count',
                'realtime_service_status'
			],
			"smtp_mail_relay" => [
				'smtp_mail_relay_view',
				'smtp_mail_relay_add',
				'smtp_mail_relay_update',
				'smtp_mail_relay_delete'
			],
			"manage_mail_relay" => [
				'manage_mail_relay_view',
				'manage_mail_relay_register',
				'manage_mail_relay_edit',
				'manage_mail_relay_delete'
			],
            "mail_upstream_routing" => [
               'mail_upstream_routing_view',
            ],
			// "history_connection" => [
			// 	'view_blacklist_firewall',
			// 	'view_whitelist_firewall',
			// 	'view_fail_to_ban_blacklist_firewall'
			// ],
			// "uc_overview" => [
			// 	'view_register_application_ip',
			// 	'view_register_domain_map',
			// 	'view_register_username',
			// 	'view_list_wildcard'
			// ],
			"server_overview" => [
				'server_overview_view',
				"manage_service" => [
					"manage_service_view",
					"manage_service_update"
				]
			],
			"log" => [
				"mail_log" => [
					'mail_log_view',
				],
				"spmr_activity_log" => [
					'spmr_activity_log_view',
				],
			],
			"report" => [
				// "traffic_statistic_summary" => [
				// 	'traffic_statistic_summary_view',
				// 	'traffic_statistic_summary_download',
				// ],
				// "detail_report_sender" => [
				// 	'detail_report_sender_view',
				// 	'detail_report_sender_download',
				// ],
				// "query_log" => [
				// 	'query_log_view',
				// 	'query_log_download',
				// ],
                "agencies_report" => [
					'agencies_report_view',
				],
                "usage_report"=>[
                    'usage_report_view',
                ]
			],
			"configuration" => [
				"server" => [
					'server_view',
					'server_add',
					'server_update',
					'server_delete',
				],
				"application_user" => [
					'user_view',
					'user_add',
					'user_update',
					'user_delete',
				],
				"role_permission" => [
					'role_permission_view',
					'role_permission_add',
					'role_permission_update',
					'role_permission_delete',
				],
				// "domain_name" => [
				// 	'domain_name_view',
				// 	'domain_name_add',
				// 	'domain_name_update',
				// 	'domain_name_delete',
				// ],
				// "position_department" => [
				// 	'position_department_view',
				// 	'position_department_add',
				// 	'position_department_update',
				// 	'position_department_delete',
				// ],
				"ministry" => [
					'ministry_view',
					'ministry_add',
					'ministry_update',
					'ministry_delete',
				],
				"agencies" => [
					'agencies_view',
					'agencies_add',
					'agencies_update',
					'agencies_delete',
				],
				// "mail_upstream" => [
				// 	'mail_upstream_view',
				// 	'mail_upstream_add',
				// 	'mail_upstream_update',
				// 	'mail_upstream_delete',
				// ],
				"version" => [
					'version_view'
				],
			],
			"validation_verification" => [
				"loopback_smtp_test" => [
					'loopback_smtp_test_view'
				],
				// "replication_status" => [
				// 	'replication_status_view'
				// ],
				// "print_list_ip_address" => [
				// 	'print_list_ip_address_view'
				// ],
			],
		];

        // Step 1: Delete records starting from ID 1
       // Truncate the permissions table
        Permission::truncate();

        // Reset auto-increment to start with 1
        DB::statement('ALTER SEQUENCE permissions_id_seq RESTART WITH 1;');

		foreach ($permissions as $key => $permission) {
            foreach ($permission as $k => $p) {
                if(is_array($p) && count($p)>0) {
                    foreach ($p as $l => $j) {
                       $permisi = new Permission();
                       $permisi -> name = $j;
                       $permisi -> group = $key;
                       $permisi -> sub_group = $k;
                       $permisi -> guard_name ='web';
                       $permisi -> save();
                    }
                }
                else{
                    $permisi = new Permission();
                    $permisi -> name = $p;
                    $permisi -> group = $key;
                    $permisi -> sub_group = '';
                    $permisi -> guard_name ='web';
                    $permisi -> save();
                }
            }
		}

		// Create Roles
		$roles = [
			'Developer',
			'Superadmin',
			'Admin'
		];

        DB::table('role_has_permissions')->truncate();

		foreach ($roles as $roleName) {
            // Check if the role already exists
            $role = Role::where(['name' => trim($roleName), 'guard_name' => 'web'])->first();

            // If the role doesn't exist, create it
            if (!$role) {
                $role = Role::create(['name' => trim($roleName), 'guard_name' => 'web']);
            }

            if ($role->name == 'Superadmin' || $role->name == 'Developer') {
                // assign all permissions
                $role->syncPermissions(Permission::all());
            } else {
                // for others by default only read access
                $role->syncPermissions(Permission::where('name', 'LIKE', 'lihat_%')->get());
            }
        }



		$servers = Server::get();
		$roles = Role::get();
		// Truncate the role_server table before re-populating it
            RoleServer::truncate();

            foreach ($roles as $role) {
                foreach ($servers as $server) {
                    $serversave = new RoleServer;
                    $serversave->role_id  = $role->id;
                    $serversave->server_id = $server->id;
                    $serversave->save();
                }
            }
	}
}
