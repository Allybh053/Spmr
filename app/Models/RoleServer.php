<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleServer extends Model
{
    protected $table = 'role_servers';
    protected $fillable = ['role_id', 'server_id'];
    protected $primaryKey = null; // Set the primary key to null to indicate no primary key
    
    public $incrementing = false; 
    public $timestamps = false;
}
