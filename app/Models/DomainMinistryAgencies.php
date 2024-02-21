<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class DomainMinistryAgencies extends Model
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    // Specify the table name
    protected $table = 'domain_ministry_agencies_list';

    // Specify the fillable attributes
    protected $fillable = [
        'domain_name',
        'ministry_name',
        'agencies_name',
        'remarks',
        'enabled',
    ];

    // If your table has timestamps (created_at and updated_at columns)
    public $timestamps = true;
}
