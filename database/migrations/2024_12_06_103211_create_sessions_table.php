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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('client_ip')->nullable();
            $table->string('session_id');
            $table->text('token')->nullable();
            $table->text('url')->nullable();
            $table->text('previous_url')->nullable();
            $table->text('flash_old')->nullable();
            $table->text('flash_new')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
