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
        Schema::create('smtp_relay_req_form', function (Blueprint $table) {
            $table->id();
            $table->string('form_id');
            $table->string('ticket_id');
            $table->integer('types_req')->length(11);
            $table->string('project_name');
            $table->string('fqdn_system');
            $table->string('mailform_email_addr');
            $table->string('system_owner_name');
            $table->tinyInteger('mailbox_created')->length(11);
            $table->string('owner_email_addr');
            $table->string('owner_mobile');
            $table->string('agencies');
            $table->string('dept_div_unit');
            $table->string('agencies_addr');
            $table->string('req_justifications');
            $table->unsignedInteger('est_no_system_users_per_day');
            $table->unsignedInteger('est_no_email_sent_from_system_users_per_day');
            $table->string('applicant_name');
            $table->string('applicant_position');
            $table->string('applicant_dept_div');
            $table->dateTime('date_application');
            $table->string('verifier_1_name');
            $table->string('verifier_1_position');
            $table->string('verifier_1_dept_div');
            $table->dateTime('date_verification_1');
            $table->string('verifier_2_name');
            $table->string('verifier_2_position');
            $table->string('verifier_2_dept_div');
            $table->dateTime('date_verification_2');
            $table->string('verifier_3_name');
            $table->string('verifier_3_position');
            $table->string('verifier_3_dept_div');
            $table->dateTime('date_verification_3');
            $table->string('implementation_name');
            $table->tinyInteger('implementation_status');
            $table->dateTime('date_of_implementation');
            $table->dateTime('last_updated');
            $table->string('ehlo_hostname');
            $table->longText('comments')->nullable();
            $table->integer('history');
            $table->string('detected_mailfrom');
            $table->string('upload_copy_attachment')->nullable();
            // Add any additional columns or modifications as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp_relay_req_form');
    }
};
