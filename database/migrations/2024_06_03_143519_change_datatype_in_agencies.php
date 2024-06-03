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
        // Convert existing data to integer explicitly specifying the conversion
        DB::statement('ALTER TABLE agencies ALTER COLUMN ministry TYPE integer USING (COALESCE(NULLIF(ministry, \'\'), \'0\')::integer)');

        // Set the column to not nullable
        Schema::table('agencies', function (Blueprint $table) {
            $table->integer('ministry')->nullable(false)->change();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {

        });
    }
};
