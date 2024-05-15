<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStripe extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'cus_id',
        'card_id',
        'subscription_id',
        'plan_id',
        'price_id'
    ];

    public function scopeActive($query)
    {

    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
