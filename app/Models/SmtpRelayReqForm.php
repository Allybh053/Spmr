<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class SmtpRelayReqForm extends Model  implements Auditable
{
    use AuditableTrait;

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    // Specify the table name
    protected $table = 'smtp_relay_req_form';

    // Specify the fillable attributes
    protected $fillable = [
        'form_id',
        'ticket_id',
        'types_req',
        'project_name',
        'server_name',
        'ip_addr_server',
        'fqdn_system',
        'mailfrom_email_addr',
        'mailbox_created',
        'system_owner_name',
        'owner_email_addr',
        'owner_phone_number',
        'ministry',
        'agencies',
        'dept_div_unit',
        'agencies_addr',
        'req_justifications',
        'smtp_relay_auth_method',
        'smtp_relay_server',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'est_no_system_users_per_day',
        'est_no_email_sent_from_system_users_per_day',
        'applicant_name',
        'applicant_position',
        'applicant_dept_div',
        'date_application',
        'verifier_1_name',
        'verifier_1_position',
        'verifier_1_dept_div',
        'date_verification_1',
        'verifier_2_name',
        'verifier_2_position',
        'verifier_2_dept_div',
        'date_verification_2',
        'verifier_3_name',
        'verifier_3_position',
        'verifier_3_dept_div',
        'date_verification_3',
        'upload_copy_attachment',
        'status',
        'sub_smtp_relay_server'

    ];

    // If your table has timestamps (created_at and updated_at columns)
    public $timestamps = true;
}
