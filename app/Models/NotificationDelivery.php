<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filters;

class NotificationDelivery extends Model
{
    use HasFactory,Filters;

    protected $fillable = [
                            'start_at',
                            'title',
                            'content',
                            'destination'
                        ];

     protected $casts = [
                            'start_at' => 'datetime',
                        ];

    public function getDestiAttribute()
    {
        switch($this->destination){
            case 'teacher' : return '講師のみ'; break;
            case 'student': return '生徒のみ'; break;
            default: return 'すべての会員に公開';
        }
    }

}
