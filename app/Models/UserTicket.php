<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id ',
        'amount',
        'status',
        'note',
        'action'
    ];

}
