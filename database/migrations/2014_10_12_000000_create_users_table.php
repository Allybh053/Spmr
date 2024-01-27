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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->integer('role_id')->length(11)->nullable();
            $table->integer('acct_types')->length(11)->nullable();
            $table->integer('enabled')->length(11)->default(0);           
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();    
            $table->integer('ldap_authentication')->length(11)->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
