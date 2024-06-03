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
            $table->string('mailfrom')->nullable()->after('ip_addr_server');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smtp_relay_server', function (Blueprint $table) {
            //
        });
    }
};
