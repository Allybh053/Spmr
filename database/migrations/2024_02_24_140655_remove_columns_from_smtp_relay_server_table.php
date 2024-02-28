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
        Schema::table('smtp_relay_server', function (Blueprint $table) {
            $table->dropColumn(['smtp_relay_auth_method', 'smtp_port', 'smtp_username', 'smtp_password','smtp_relay_server']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smtp_relay_server', function (Blueprint $table) {
            $table->integer('smtp_relay_auth_method')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('smtp_relay_server')->nullable();
        });
    }
};
