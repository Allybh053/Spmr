<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadual10 extends Model
{
    use HasFactory;
    protected $table = 'jadual10';

    protected $fillable = [
        'email', 'total_email', 'server', 'year', 'month'
    ];
}
