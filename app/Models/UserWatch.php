<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWatch extends Model
{
    use HasFactory;

    protected $table = 'user_watches';

    // protected $appends = ['minutes', 'time'];

    //minute
    public function getMinutesAttribute()
    {
        return round( $this->seconds / 60 , 2);
    }

    //23時間54分21秒
    public function getTimeAttribute()
    {
        return gmdate( 'H時間i分s秒',$this->seconds );
    }

    protected $fillable = [
        'user_id',
        'video_id',
        'request_id',
        'seconds',
        'status'
    ];

}
