<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Jadual6 extends Model  implements Auditable
{
    use HasFactory,AuditableTrait;
    protected $table = 'jadual6';

    protected $fillable = [
        'time', 'received', 'delivered', 'deferred', 'bounced', 'rejected', 'server', 'year', 'month'
    ];
}
