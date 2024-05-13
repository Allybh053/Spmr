<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class DomainServer extends Model implements Auditable
{
    use AuditableTrait;

    use HasFactory, SoftDeletes;

    protected $table = 'domain_server';

    protected $fillable = [
        'name',
        'server_id',
        'smtp',
        'status',
    ];
}
