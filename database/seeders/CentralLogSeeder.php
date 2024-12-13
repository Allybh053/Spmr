<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CentralLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable auditing
        config(['audit.enabled' => false]);

        $permissions = [
            "log" => [
                "central_log" => [
                    'central_log_view',
                ],
            ],

        ];

        foreach ($permissions as $key => $permissionGroup) {
            foreach ($permissionGroup as $subGroupKey => $permission) {
                if (is_array($permission) && count($permission) > 0) {
                    foreach ($permission as $individualPermission) {
                        Permission::updateOrCreate(
                            [
                                'name' => $individualPermission,
                                'guard_name' => 'web'
                            ],
                            [
                                'group' => $key,
                                'sub_group' => $subGroupKey
                            ]
                        );
                    }
                } else {
                    Permission::updateOrCreate(
                        [
                            'name' => $permission,
                            'guard_name' => 'web'
                        ],
                        [
                            'group' => $key,
                            'sub_group' => ''
                        ]
                    );
                }
            }
        }


        // Create Roles
        $roles = [
            'Developer',
            'Superadmin',
        ];


        foreach ($roles as $roleName) {
            // Create or retrieve the role
            $role = Role::firstOrCreate(
                [
                    'name' => trim($roleName),
                    'guard_name' => 'web'
                ]
            );

            if ($role->name === 'Superadmin' || $role->name === 'Developer') {
                // Assign all permissions to Superadmin and Developer
                $role->syncPermissions(Permission::all());
            } else {
                // Assign read-only permissions to other roles
                $role->syncPermissions(Permission::where('name', 'LIKE', 'session_view%')->get());
            }
        }

        // Re-enable auditing
        config(['audit.enabled' => true]);
    }
}
