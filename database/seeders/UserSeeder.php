<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
    	$user = new User;
    	$user->username     = 'Spmr_dev';
    	$user->email    = 'spmr@spmr.com.my';
    	$user->password = Hash::make('spmr1234');
    	$user->save();
    }
}
