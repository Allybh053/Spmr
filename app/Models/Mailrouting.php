<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mailrouting extends Model
{
    use HasFactory;

    protected $table = 'mailrouting';

    protected $fillable = [
        'server', 'source', 'destination', 'status'
    ];
}
