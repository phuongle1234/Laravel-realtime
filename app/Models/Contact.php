<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_contact_id',
        'request_id ',
        'message_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_contact_id','id');
    }

    public function getMessageContentAttribute()
    {

        if( ! $this->message )
            return null;

        if(! $this->message->content)
            return null;

        $_content = json_decode($this->message->content,true);

        $last_index = $_content[count($_content)-1]['message'];

        return $last_index;

//        return mb_substr( $last_index, 0, 20,'UTF-8')."...";
    }

    public function message()
    {
        return $this->hasOne(Message::class,'id','message_id');
    }

    public function request()
    {
        return $this->hasOne(Request::class,'id','request_id');
    }

}
