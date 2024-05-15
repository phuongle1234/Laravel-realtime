<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserView extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_id',
        'video_id',
        'status'
    ];

}
