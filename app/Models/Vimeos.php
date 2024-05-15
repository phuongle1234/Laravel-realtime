<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vimeos extends Model
{
    protected $table = 'vimeos';
    protected $fillable  = [
        'client_id',
        'client_secrets',
        'personal_access_token'
    ];

    public function scopeActive($value)
    {
        
    }

}
