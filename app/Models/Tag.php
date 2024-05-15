<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filters;

class Tag extends Model
{
    use HasFactory, Filters;
    public $prefix;

    protected $fillable = [
        'name',
        'tag_type',
        'subject_id',
        'active'
    ];

    public function getTypeAttribute()
    {
        switch($this->tag_type){
            case 'field': return '分野'; break;
            default: return '難易度';
        }
    }

    public function getStatusAttribute()
    {
        switch($this->active){
            case 1: return '表示'; break;
            default: return '非表示';
        }
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id','id');
    }

}
