<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servers', function (Blueprint $table) {

            $table->string('hostname', 255)->nullable()->change();
            $table->string('ipaddress', 255)->nullable()->change();
            $table->string('sshusername', 255)->nullable()->change();
            $table->string('sshkey', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servers', function (Blueprint $table) {
            //
        });
    }
};
