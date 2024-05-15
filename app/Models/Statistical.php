<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistical extends Model
{
    //use HasFactory;

    protected $table = 'view_statistical';

    protected $fillable = [
        "id",
        "requests",
        "likes",
        "views",
        "seconds",
        "month"
    ];
}
