<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Jadual14 extends Model  implements Auditable
{
    use HasFactory,AuditableTrait;
    protected $table = 'jadual14';

    protected $fillable = [
        'email', 'total_email', 'server', 'year', 'month'
    ];
}
