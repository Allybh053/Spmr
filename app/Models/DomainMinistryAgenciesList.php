<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainMinistryAgenciesList extends Model
{
    use HasFactory;

    protected $table = 'domain_ministry_agencies_lists'; // Adjust the table name if needed
    protected $fillable = ['domain_name', 'ministry_name', 'agencies_name','enabled','created_at','updated_at'];
}
