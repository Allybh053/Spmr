<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadual6 extends Model
{
    use HasFactory;
    protected $table = 'jadual6';

    protected $fillable = [
        'time', 'received', 'delivered', 'deferred', 'bounced', 'rejected', 'server', 'year', 'month'
    ];
}
