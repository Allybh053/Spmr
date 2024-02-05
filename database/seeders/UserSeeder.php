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
    	$user = new User;
    	$user->username     = 'spmr_dev';
    	$user->email    = 'spmr@spmr.com.my';
    	$user->password = Hash::make('spmr1234');
        $user->enabled = 1;
    	$user->save();
    }
}
