<?php

namespace App\Helper;

use App\Enums\EStatus;
use Carbon\Carbon;

class Helper
{
    const GIGABYTE = 1073741824;
    const MEGABYTE = 1048576;

    public static function renderDateTime($value, $extra_hour = false)
    {
        $format = "Y年m月d日";
        if($extra_hour) $format .= " H:i";
        return Carbon::parse($value)->format($format);
    }

    public static function renderTotalPlayTime($value)
    {
        return gmdate('H時間i分s秒',$value);
    }

    public static function renderStatisticalDate($year,$month)
    {
        $html = "";
        $specified_date = Carbon::createFromDate($year, $month,1);

        $html .= $specified_date->firstOfMonth()->format('Y年m月d日');
        $html .= "<br>";
        $html .= "～<br>";
        $html .= $specified_date->lastOfMonth()->format('Y年m月d日');

        return $html;
    }

    public static function renderColorRequestStatus($status)
    {
        switch ($status){
            case EStatus::ACCEPTED:
                $style = "color: #4197E5;";
                break;
            case EStatus::DELAYED:
                $style = "color: #EC5F56;";
                break;
            case EStatus::APPROVED:
                $style = "color: #00A29A;";
                break;
            case EStatus::PASS:
                $style = "color: #C1DA8C;";
                break;
            case EStatus::DENIED:
                $style = "color: #FAAA4D;";
                break;
            case EStatus::COMPLETE:
                $style = "color: #3F3B3A;";
                break;
            default:
                $style = "";
                break;
        }
        return $style;
    }

    public static function calculateDiskTotalSize($total_size_value)
    {
        return round($total_size_value / self::GIGABYTE);
    }

    public static function calculateDiskCurrentUsedSize($current_used_size_value)
    {
        return round($current_used_size_value / self::GIGABYTE,2);
    }

    public static function renderVimeoStorage($max_size_value,$used_size_value)
    {
        $disk_total_size = self::calculateDiskTotalSize($max_size_value);
        $disk_current_used_size = self::calculateDiskCurrentUsedSize($used_size_value);

        return $disk_current_used_size . '/' . $disk_total_size . 'GB';
    }

}