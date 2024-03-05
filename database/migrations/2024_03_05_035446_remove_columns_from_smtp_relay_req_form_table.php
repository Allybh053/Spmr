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
        Schema::table('smtp_relay_req_form', function (Blueprint $table) {
            $table->dropColumn(['server_name', 'ip_addr_server', 'fqdn_system', 'implementation_status', 'date_of_implementation', 'last_updated']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smtp_relay_req_form', function (Blueprint $table) {
            $table->string('server_name')->nullable();
            $table->string('ip_addr_server')->nullable();
            $table->string('fqdn_system')->nullable();
            $table->tinyInteger('implementation_status')->nullable();
            $table->dateTime('date_of_implementation')->nullable();
            $table->dateTime('last_updated')->nullable();

        });
    }
};
