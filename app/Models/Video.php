<?php

namespace App\Models;

use App\Enums\EStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Filters;

class Video extends Model
{
    use HasFactory, Filters, SoftDeletes;

    public $prefix = 'admin-video';

    protected $appends = ['completion_date'];

    protected $dates = ['updated_at'];

    protected $fillable  = [
        'owner_id',
        'vimeo_id',
        'user_vimeo_id',
        'title',
        'description',
        'path',
        'private',
        'thumbnail',
        'player_embed_url',
        'transcode',
        'is_submit',
        'active'
    ];
    public function getNamePathAttribute()
    {
        $_file_name = explode('/', $this->path);
        $_file_name = $_file_name[count($_file_name) - 1];
        return  $_file_name;
    }

    public function getCompletionDateAttribute()
    {
       return $this->created_at->format('Y年m月d日 H:i');
    }

    public function requestApproved(){
        return $this->hasOne(request::class,'video_id','id')->where('status',EStatus::COMPLETE);
    }

    public function request(){
        return $this->hasMany(request::class,'video_id','id');
    }

    public function likes()
    {
        return $this->hasMany(UserLike::class,'video_id','id')->where('status',EStatus::ACTIVE);
    }

    public function views()
    {
        return $this->hasMany(UserView::class,'video_id','id')->where('status',EStatus::ACTIVE);
    }

    public function watchs()
    {
        return $this->hasMany(UserWatch::class,'video_id','id')->where('status',EStatus::ACTIVE);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }
    public function scopeActive($query)
    {
    }
}
