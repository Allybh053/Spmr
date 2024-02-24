<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Server extends Model
{
    use HasFactory;
    
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
