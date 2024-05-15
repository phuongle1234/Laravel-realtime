<?php

namespace App\Models;
use Excel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolMaster extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'university_Code',
        'faculty_code',
        'university_name',
        'faculty_name'
    ];


     // query get university
     public function university()
     {
         return $this->distinct()->get(['university_name as name','university_code as code']);
     }

     // query get faculty
     public function faculty( $_university_Code )
     {
         return $this->where('university_Code', $_university_Code)->get(['id','faculty_name', 'faculty_code']);
     }

     public function university_name($_university_Code)
     {
        return $this->distinct()->where('university_Code', $_university_Code)->first();

     }
}
