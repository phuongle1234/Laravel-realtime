<?php

namespace App\Models;

use App\Helper\Helper;
use App\Traits\Filters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCompensationsLogs extends Model
{
    use HasFactory,SoftDeletes;
    const FORMAT_HOUR_MINUTE = ' 23:59:59';

    protected $fillable = [
        'total_amount_exported',
        'file_path_name',
        'status',
        'note'
    ];

    // search filter
    public function scopeSearch($query,$conditions)
    {
        $from_date = $conditions['from_date'] . self::FORMAT_HOUR_MINUTE;
        $to_date = $conditions['to_date'] . self::FORMAT_HOUR_MINUTE;
        return $query->whereBetween('created_at',[$from_date,$to_date]);
    }

    // relationship
    public function compensations()
    {
        return $this->hasMany(UserCompensations::class,'log_id','id');
    }

    public function getTotalRecordsAttribute()
    {
        return $this->compensations()->count();
    }
}
