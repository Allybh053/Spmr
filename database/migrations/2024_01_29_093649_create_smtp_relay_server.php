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
        // Create smtp_relay_server table
        Schema::create('smtp_relay_server', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id');
            $table->string('server_name');
            $table->string('ip_addr_server');
            $table->tinyInteger('smtp_relay_auth_method')->length(11);
            $table->string('smtp_relay_server');
            $table->integer('smtp_port')->length(11);
            $table->string('smtp_username');
            $table->string('smtp_password');
            // Add any additional columns or modifications as needed
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop smtp_relay_server table
        Schema::dropIfExists('smtp_relay_server');
    }
};
