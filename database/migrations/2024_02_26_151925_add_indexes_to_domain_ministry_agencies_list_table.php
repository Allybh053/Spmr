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
        Schema::table('domain_ministry_agencies_list', function (Blueprint $table) {
            $table->index('domain_name');
            $table->index('ministry_name');
            $table->index('agencies_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domain_ministry_agencies_list', function (Blueprint $table) {
            //
        });
    }
};
