<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PhpParser\Node\Stmt\Return_;

trait Filters
{

    // build scope for model
    public function scopeSearch($query, $dataFilter)
    {
        return $this->apply($query, $dataFilter);
    }

    private function apply(Builder $builder, $dataFilter)
    {

        foreach($dataFilter as $key =>$value){
            if( $value || is_numeric($value) )
              $this->whereColumn($builder, $key, $value);
        }
        return $builder;
    }

    private function whereColumn(Builder $builder, $_key_name, $value){

        $_prefix = $this->prefix;

        switch($_prefix){
            case 'admin-teacher' :

                if($_key_name == 'subject_id' )
                    return $builder->whereHas('subject', function ($query) use ($_key_name,$value) { $query->where('subjects.id','like',"%{$value}%" );  });

                    if($_key_name == 'approved_admin' )
                    return $builder->where( $_key_name, $value );

                    if($_key_name == 'percent_training' )
                    {
                        if($value == 1)
                            return  $builder->where($_key_name,100);

                        return $builder->whereNotIn($_key_name,["100"] );

                    }

                break;
            case 'student-teacher':
                if($_key_name == 'subject_id')
                    return $builder->whereHas('userSubject', function ($query) use($value) { $query->where("subject_id", $value);  });
                if($_key_name == 'university_Code')
                    return $builder->where($_key_name,$value);

                break;
            case 'student-teacher-list' :
                if($_key_name == 'subject_id' )
                    return $builder->whereHas('subject', function ($query) use ($_key_name,$value) { $query->where('subjects.id','like',"%{$value}%" );  });
                break;

            // video
            case 'admin-video' :
                if( $_key_name == 'teacher_name' )
                    return $builder->whereHas('user', function ($query) use ($_key_name,$value) { $query->where('users.name','like',"%{$value}%");});
                break;
            case 'admin-video-views' :

                if($_key_name == 'date-time')
                // explode('-',$value)
                    return $builder->whereBetween('videos.created_at', $value );

                if($_key_name == 'key_work' ){

                    return $builder->where(function($query) use($value){
                            $query->Where('title','like' ,"%{$value}%")
                                  //vd_title
                                  ->orWhere('videos.description','like' ,"%{$value}%")
                                //   ->orWhereHas( 'user', function( $_query ) use($value) { return $_query->Where('name','like' ,"%{$value}%");  } );
                                  ->orWhere('users.name','like' ,"%{$value}%");
                                //   ->orWhere('content','like' ,"%{$value}%");
                    });
                }
                break;
            case 'student-reuqest-video' :

                if($_key_name == 'key_work' ){
                    return $builder->where(function($query) use($value){
                            $query->Where('vd_title','like' ,"%{$value}%")
                                  ->orWhere('content','like' ,"%{$value}%");
                    });
                }

                  return $builder->where($_key_name,$value);
                break;
            case 'admin-student':
                if($_key_name == 'plan_id'){
                    return $builder->whereHas('stripe',function ($q) use ($value){
                        $q->where('plan_id',$value);
                    });
                }
                break;
        }

        //check equal sign --eSign    ex: eSign_status

        if( substr($_key_name,0,5) == 'eSign' )
            return $builder->where( substr($_key_name,6), $value );

        return $builder->where($_key_name, 'like', "%{$value}%");

    }
}

