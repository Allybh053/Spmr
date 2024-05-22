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
        Schema::create('jadual6', function (Blueprint $table) {
            $table->id();
            $table->string('time');
            $table->bigInteger('received');
            $table->bigInteger('delivered');
            $table->integer('deferred');
            $table->integer('bounced');
            $table->bigInteger('rejected');
            $table->string('server');
            $table->integer('year');
            $table->integer('month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadual6');
    }
};
