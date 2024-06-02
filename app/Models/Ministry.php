<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Ministry extends Model  implements Auditable
{
    use HasFactory, SoftDeletes,AuditableTrait;

    protected $table = 'ministrys';

    protected $fillable = ['name', 'remark', 'status','code_name'];

    protected $casts = [
        'status' => 'integer',
    ];

    protected $dates = ['deleted_at'];
}
