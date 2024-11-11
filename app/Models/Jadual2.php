<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Jadual2 extends Model  implements Auditable
{
    use HasFactory, AuditableTrait;
    protected $table = 'jadual2';

    protected $fillable = [
        'date', 'received', 'delivered', 'deferred', 'bounced', 'rejected', 'server', 'year', 'month'
    ];
}
