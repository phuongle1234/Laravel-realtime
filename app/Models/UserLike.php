<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_id',
        'request_id',
        'status'
    ];

}
