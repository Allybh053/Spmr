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
        'status',
        'server_type',
        'status_cluster',
        'master_id',
        'subhostname'
    ];

    public function roles() {
        return $this->belongsToMany(Role::class, 'roleServer', 'server_id', 'role_id');
    }

    // Define the relationship to get sub servers
    public function subServers()
    {
        return $this->hasMany(Server::class, 'master_id');
    }

    // Define the relationship to get the master server
    public function masterServer()
    {
        return $this->belongsTo(Server::class, 'master_id');
    }
}
