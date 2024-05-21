<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            ['username' => 'spmr_superadmin', 'email' => 'spmr-superadmin@digital.gov.my','role_id'=>'2'],
            ['username' => 'spmr_dev', 'email' => 'spmr-dev@digital.gov.my','role_id'=>'1'],
            ['username' => 'spmr_admin', 'email' => 'spmr-admin@digital.gov.my','role_id'=>'2']
        ];

        $password = Hash::make('spmr1234');
        $enabled = 1;

        foreach ($users as $userData) {
            $user = new User;
            $user->username = $userData['username'];
            $user->email = $userData['email'];
            $user->password = $password;
            $user->enabled = $enabled;
            $user->role_id = $userData["role_id"];
            $user->save();
        }
    }
}
