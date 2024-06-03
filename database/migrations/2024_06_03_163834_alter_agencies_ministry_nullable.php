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
            $table->string('ministry')->nullable()->change();
            $table->string('agencies')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smtp_relay_req_form', function (Blueprint $table) {
            $table->string('ministry')->change();
            $table->string('agencies')->change();
        });
    }
};
