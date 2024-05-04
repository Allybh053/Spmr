<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ministry extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ministrys';

    protected $fillable = ['name', 'remark', 'status'];

    protected $casts = [
        'status' => 'integer',
    ];

    protected $dates = ['deleted_at'];
}
