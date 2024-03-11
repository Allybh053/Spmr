<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class smtp_relay_server extends Model  implements Auditable
{
    use AuditableTrait;

    use HasFactory;
    protected $table = 'smtp_relay_server';

    protected $fillable = [
        'form_id',
        'server_name',
        'ip_addr_server',
        'fqdn_server',
        'smtp_relay_server',
        'smtp_port',
        'smtp_username',
        'smtp_password',
    ];

    // If your table has timestamps (created_at and updated_at columns)
    public $timestamps = true;
}
