<?php

namespace App\Services;

use App\Models\UserWatch;
use Auth;
use Illuminate\Http\Request;
use DB;

class StatisticsService extends BaseService
{


    // public function __construct()
    // {

    // }

    public function totalViewByMonth(array $_time)
    {
        try {

            // $_time = [8,2021]

            $_user = Auth::guard('student')->user();

            $_item = $_user->watches()
                     ->selectRaw('SUM(seconds) AS seconds, EXTRACT(DAY FROM `created_at`) as day')
                     ->groupBy( DB::raw(' EXTRACT(DAY FROM `created_at`) ') )
                     ->whereRaw('EXTRACT(MONTH FROM `created_at`) = ? and EXTRACT(YEAR FROM `created_at`) = ? ',$_time)
                     ->get();

            return $_item;

        } catch (Throwable $e) {
            report($th->getMessage());
        }

        return $event;
    }

    public function totalViewByDay(array $_time)
    {
        try {

            // $_time = [9,8,2021] d-m-Y

            $_user = Auth::guard('student')->user();

            $_item = $_user->watches()
                     ->selectRaw('SUM(seconds) AS seconds, EXTRACT(HOUR FROM `created_at`) as hour')
                     ->groupBy( DB::raw(' EXTRACT(HOUR FROM `created_at`) ') )
                     ->whereRaw('EXTRACT(DAY FROM `created_at`) = ? AND EXTRACT(MONTH FROM `created_at`) = ? and EXTRACT(YEAR FROM `created_at`) = ? ',$_time)
                     ->get();

            return $_item;

        } catch (Throwable $e) {
            report($th->getMessage());
        }

        return $event;
    }

}
