<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DB;

class Subject extends Model
{
    use HasFactory;
    protected $appends = ['image', 'text_name'];
     /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */


    public function getTextNameAttribute(): string
    {
        return str_replace('<br />','', $this->name);
    }

    public function scopeGetAllId($query)
    {
        return collect( $query->get()->toArray() )
                ->map(function ( $item, $key) { return $item['id']; })->toArray();
    }

    public function getImageAttribute()
    {

        return asset($this->icon);
    }

    public function userSubject(){
        return hasMany(UserSubject::class,'id','user_id');
    }

    public function scopeMapTeacher($query, $block)
    {

        return $query->Join('user_subjects','subjects.id','=','user_subjects.subject_id')
                     ->groupBy('subjects.id','subjects.name', 'subjects.icon')
                     ->select('subjects.id',
                              'subjects.name',
                              'subjects.icon'
                              )
                    ->whereNotIn('user_subjects.user_id',$block)
                     ->get();

    }

    public function tags()
    {
        return $this->hasMany(Tag::class,'subject_id','id');
    }

}
