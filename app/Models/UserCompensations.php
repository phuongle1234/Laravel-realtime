<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompensations extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'log_id',
        'amount',
        'status',
        'note'
    ];

    // relationship
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function log()
    {
        return $this->belongsTo(UserCompensationsLogs::class,'log_id','id');
    }
}
