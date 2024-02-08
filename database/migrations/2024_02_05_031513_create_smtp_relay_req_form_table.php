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
            $table->string('ticket_id')->nullable();
            $table->integer('types_req')->length(11)->nullable();
            $table->string('project_name')->nullable();
            $table->string('server_name')->nullable();
            $table->string('ip_addr_server')->nullable();
            $table->string('fqdn_system')->nullable();
            $table->string('mailfrom_email_addr')->nullable();
            $table->tinyInteger('mailbox_created')->length(11)->nullable();
            $table->string('system_owner_name')->nullable();
            $table->string('owner_email_addr')->nullable();
            $table->string('owner_phone_number')->nullable();
            $table->string('agencies')->nullable();
            $table->string('dept_div_unit')->nullable();
            $table->string('agencies_addr')->nullable();
            $table->string('req_justifications')->nullable();
            $table->tinyInteger('smtp_relay_auth_method')->nullable();
            $table->string('smtp_relay_server')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->unsignedInteger('est_no_system_users_per_day')->nullable();
            $table->unsignedInteger('est_no_email_sent_from_system_users_per_day')->nullable();
            $table->string('applicant_name')->nullable();
            $table->string('applicant_position')->nullable();
            $table->string('applicant_dept_div')->nullable();
            $table->dateTime('date_application')->nullable();
            $table->string('verifier_1_name')->nullable();
            $table->string('verifier_1_position')->nullable();
            $table->string('verifier_1_dept_div')->nullable();
            $table->dateTime('date_verification_1')->nullable();
            $table->string('verifier_2_name')->nullable();
            $table->string('verifier_2_position')->nullable();
            $table->string('verifier_2_dept_div')->nullable();
            $table->dateTime('date_verification_2')->nullable();
            $table->string('verifier_3_name')->nullable();
            $table->string('verifier_3_position')->nullable();
            $table->string('verifier_3_dept_div')->nullable();
            $table->dateTime('date_verification_3')->nullable();
            $table->string('implementation_name')->nullable();
            $table->tinyInteger('implementation_status')->nullable();
            $table->dateTime('date_of_implementation')->nullable();
            $table->dateTime('last_updated')->nullable();
            $table->string('ehlo_hostname')->nullable();
            $table->longText('comments')->nullable()->nullable();
            $table->integer('history')->nullable();
            $table->string('detected_mailfrom')->nullable();
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
