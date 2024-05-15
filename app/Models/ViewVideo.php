<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\Filters;

class ViewVideo extends Model
{
    use HasFactory, Filters;

    public $prefix;

    protected $appends = ['expires_at'];

    protected $casts = [
        'deadline' => 'datetime',
        'updated_at' => 'datetime',
        'vd_created_at' => 'datetime'
    ];

    protected $table = 'view_videos';

    public function getExpiresAtAttribute()
    {
        return $this->updated_at->format('Y年m月d日 H:i');
    }

}
