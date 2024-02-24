<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Server;
use App\Models\RoleServer;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AclSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run()
	{
		// Create Permissions
		$permissions = [
			"smtp_mail_relay" => [
				'view',
				'add',
				'update',
				'delete'
			],
			"manage_mail_relay" => [
				'view',
				'register',
				'edit',
				'delete'
			],
			"history_connection" => [
				'view_blacklist_firewall',
				'view_whitelist_firewall',
				'view_fail_to_ban_blacklist_firewall'
			],
			"uc_overview" => [
				'view_register_application_ip',
				'view_register_domain_map',
				'view_register_username',
				'view_list_wildcard'
			],
			"log_overview_server" => [
				'view',
			],
			"report" => [
				"traffic_statistic_summary" => [
					'view',
					'download',
				],
				"detail_report_sender" => [
					'view',
					'download',
				],
			],
			"configuration" => [
				"server" => [
					'view',
					'add',
					'update',
					'delete',
				],
				"user" => [
					'view',
					'add',
					'update',
					'delete',
				],
				"role_permission" => [
					'view',
					'add',
					'update',
					'delete',
				],
			],
		];

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


		foreach($roles as $roleName) {
			$role = Role::firstOrCreate(['name' => trim($roleName), 'guard_name' => 'web']);
		
			if($role->name == 'Superadmin' || $role->name == 'Developer') {
				// assign all permissions
				$role->syncPermissions(Permission::all());
			} else {
				// for others by default only read access
				$role->syncPermissions(Permission::where('name', 'LIKE', 'lihat_%')->get());
			}
		

		}
		$servers = Server::get();
		$roles = Role::get();
		foreach ($roles as $role) {
			foreach ($servers as $server) {
				$serversave = new RoleServer;
				$serversave->role_id  = $role->id;
				$serversave->server_id = $server->id;
				$serversave->save();
			}
		}

		// Create developer user
		$user = new User;
    	$user->username  = 'spmr_dev';
    	$user->email    = 'spmr-dev@digital.gov.my';
    	$user->password = Hash::make('spmr1234');
        $user->enabled = 1;
    	$user->save();

		$user->assignRole('Developer');

		

		// Create superadmin user
		$user           = new User;
		$user->username  = 'spmr_superadmin';
    	$user->email    = 'spmr-superadmin@digital.gov.my';
    	$user->password = Hash::make('spmr1234');
        $user->enabled = 1;
    	$user->save();

		$user->assignRole('Superadmin');

		// Create admin user
		$user           = new User;
		$user->username  = 'spmr_admin';
    	$user->email    = 'spmr-admin@digital.gov.my';
    	$user->password = Hash::make('spmr1234');
        $user->enabled = 1;
    	$user->save();

		$user->assignRole('Admin');
	}
}
