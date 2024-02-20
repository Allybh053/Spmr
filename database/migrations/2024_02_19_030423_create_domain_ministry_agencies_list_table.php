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
        Schema::create('domain_ministry_agencies_list', function (Blueprint $table) {
            $table->id();
            $table->string('domain_name')->nullable();
            $table->string('ministry_name')->nullable();
            $table->string('agencies_name')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('enabled')->length(11)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_ministry_agencies_list');
    }
};
