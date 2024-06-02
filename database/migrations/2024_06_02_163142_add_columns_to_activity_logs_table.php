<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('action')->nullable()->after('user_id');
            $table->string('url')->nullable()->after('action');
            $table->string('method')->nullable()->after('url');
            $table->text('input')->nullable()->after('method');
        });

        // Update existing records to set default values for the new columns
        DB::table('activity_logs')->update([
            'action' => 'default_action', // You can set appropriate default values
            'url' => 'default_url',
            'method' => 'default_method',
        ]);

        // Alter the columns to be NOT NULL
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('action')->nullable(false)->change();
            $table->string('url')->nullable(false)->change();
            $table->string('method')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['action', 'url', 'method', 'input']);
        });
    }
};
