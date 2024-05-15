<?php

namespace App\Models;

use App\Enums\EStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use App\Traits\Filters;
use Carbon\Carbon;

class Request extends Model
{
    use HasFactory, SoftDeletes, Filters;

    public $prefix;

    protected $fillable = [
        'user_id',
        'user_receive_id',
        'file_path',
        'title',
        'content',
        'subject_id',
        'video_id',
        'request_id',
        'private',
        'status',
        'is_direct',
        'is_displayed',
        'deadline',
        'path',
        'tag_id',
        'field_id',
        'video_id',
        'video_title',
        'description',
        'ticket_id',
        'note'

    ];

    protected $casts = [
        'deadline' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $appends = ['expires_at'];

    public function getEndAtAttribute()
    {
        return $this->updated_at->format('Y年m月d日 H:i');
    }

    public function getExpiresAtAttribute()
    {
        return $this->deadline->format('Y年m月d日 H:i');
    }

    // public function getLikeVideoAttribute(){
    //     return $this->likeVideo()->count();
    // }

    public function scopeActive($query)
    {
        return $query->where('is_displayed',EStatus::IS_DISPLAYED);
    }

    public function getClassStatusAttribute()
    {

        switch($this->status){

                case EStatus::PASS:
                // case EStatus::ACCEPTED:
                // case EStatus::APPROVED:
                    return  ['class' => 'btn-pink', 'text' => '回答内容を確認する'];
                case EStatus::COMPLETE:
                    return  ['class' => 'btn-primary', 'text' => '内容を確認する'];
                default:
                    return  ['class' => 'btn-orange', 'text' => '内容を確認する'];
        }

    }

    public function getImageAttribute()
    {
        //return asset('storage/'.$item);
       return collect( json_decode($this->file_path) )->map( function($item){ return $item;   } );
    }

    public function subject()
    {
       return $this->hasOne(Subject::class,'id','subject_id');
    }

    public function video()
    {
       return $this->hasOne(Video::class,'id','video_id');
    }

    public function teacher()
    {
       return $this->hasOne(User::class,'id','user_receive_id')->where('role','teacher');
    }

    public function student()
    {
        return $this->hasOne(User::class,'id','user_id')->where('role','student');
    }

    public function likeVideo()
    {
       $_id = Auth::guard('student')->user()->id;
       return $this->hasMany(UserLike::class,'request_id','id')->where(['status' => EStatus::ACTIVE, 'user_id' => $_id ])->count();
    }

    public function like()
    {
       return $this->hasMany(UserLike::class,'video_id','video_id')->where('status',EStatus::ACTIVE);
    }

    public function tickets()
    {
       return $this->hasOne(UserTicket::class,'id','ticket_id');
    }

    public function tags()
    {
       return $this->hasOne(Tag::class,'id','tag_id');
    }

    public function fields()
    {
       return $this->hasOne(Tag::class,'id','field_id');
    }
}
