<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Department;
use App\Models\Position;

class Profile extends Model  implements Auditable
{
    use AuditableTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'email', 'role_id', 'acct_types', 'enabled',
        'last_login_at', 'last_login_ip', 'ldap_authentication','fname','lname','phone'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
