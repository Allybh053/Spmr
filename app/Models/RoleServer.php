<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Server;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class RoleServer extends Model  implements Auditable
{
    use AuditableTrait;
    
    protected $table = 'role_servers';
    protected $fillable = ['role_id', 'server_id'];
    protected $primaryKey = null; // Set the primary key to null to indicate no primary key
    
    public $incrementing = false; 
    public $timestamps = false;

    public function server() {
        return $this->belongsTo(Server::class);
    }
}
