<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSearchResult extends Model
{
    protected $fillable = [
        'hostname',
        'filename',
        'log_date',
        'ip',
        'email',
        'subject',
        'full_line',
    ];
}
