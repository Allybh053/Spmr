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
            $table->integer('agencies')->nullable(false)->change();
            $table->integer('ministry')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smtp_relay_req_form', function (Blueprint $table) {
            $table->string('agencies')->nullable()->change();
            $table->string('ministry')->nullable()->change();
        });
    }
};
