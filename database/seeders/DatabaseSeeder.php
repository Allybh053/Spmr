<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use OwenIt\Auditing\Facades\Auditor;
use OwenIt\Auditing\AuditableObserver;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // Temporarily disable auditing
       config(['audit.enabled' => false]);

       // Run your seeders
       //$this->call(ServerSeeder::class);
      //  $this->call(AclSeeder::class);
       $this->call(RolePermissionSeeder::class);
    //    $this->call(DomainMinistryAgenciesSeeder::class);

       // Re-enable auditing
       config(['audit.enabled' => true]);
    }

}
