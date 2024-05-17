<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadual2 extends Model
{
    use HasFactory;
    protected $table = 'jadual2';

    protected $fillable = [
        'date', 'received', 'delivered', 'deferred', 'bounced', 'rejected', 'server', 'year', 'month'
    ];
}
