<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Server extends Model  implements Auditable
{
    use AuditableTrait;
    
    use HasFactory, SoftDeletes;

    protected $table = 'servers';

    protected $fillable = [
        'hostname',
        'ipaddress',
        'sshusername',
        'sshkey',
        'status',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class, 'roleServer', 'server_id', 'role_id');
    }
}
