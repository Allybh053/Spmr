<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Mailrouting extends Model  implements Auditable
{
    use HasFactory,AuditableTrait;

    protected $table = 'mailrouting';

    protected $fillable = [
        'server', 'source', 'destination', 'status'
    ];
}
