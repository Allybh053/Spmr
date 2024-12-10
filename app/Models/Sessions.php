<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Sessions extends Model implements Auditable
{
    use HasFactory, AuditableTrait;

    protected $table = 'sessions';

    protected $fillable = [
        'username',
        'client_ip',
        'session_id',
        'token',
        'url',
        'previous_url',
        'flash_old',
        'flash_new',
        'created_at',
        'updated_at',
    ];
}
