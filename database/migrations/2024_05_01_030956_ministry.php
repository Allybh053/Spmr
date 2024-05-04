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
        Schema::create('ministrys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('The name of the ministry');
            $table->string('remark')->nullable();
            $table->integer('status')->default(1);
            $table->index('name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
