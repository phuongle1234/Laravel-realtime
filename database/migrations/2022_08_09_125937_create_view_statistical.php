<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Schema;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class CreateViewStatistical extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::createView( 'view_statistical',$this->createView());
    }

    // public function down()
    // {
    //     \DB::statement($this->dropView());
    // }

    private function createView(): string
    {
        // CREATE VIEW view_statistical AS
        //select

        return <<<SQL
        select u_rs.user_id, u_rs.requests, u_rs.likes, u_rs.views, u_rs.seconds, tf.amount, u_rs.month, u_rs.year   from
            ( select
                u.id as user_id,
                count(rq.id) as requests,
                sum(u_lk.likes) as likes,
                sum(u_vw.views) as views,
                sum(u_wt.seconds) as seconds,
                EXTRACT(MONTH FROM rq.created_at) as month,
                EXTRACT(YEAR FROM rq.created_at) as year
            from `requests` as rq
                LEFT JOIN (SELECT request_id, COUNT(user_id) as likes from `user_likes` group by  request_id ) as u_lk
                    ON rq.id = u_lk.request_id
                LEFT JOIN (SELECT request_id, COUNT(user_id) as views from `user_views` group by  request_id ) as u_vw
                    ON rq.id = u_vw.request_id
                LEFT JOIN (SELECT request_id, SUM(seconds) as seconds from `user_watches` group by  request_id ) as u_wt
                    ON rq.id = u_wt.request_id
                LEFT JOIN users as u
                    ON u.id = rq.user_receive_id 
            Where rq.status not in ('pending','denied')
            group by rq.user_receive_id, EXTRACT(MONTH FROM rq.created_at), EXTRACT(YEAR FROM rq.created_at)
        ) as u_rs
            LEFT JOIN (SELECT user_id, EXTRACT(MONTH FROM `created_at`) as month, EXTRACT(YEAR FROM `created_at`) as year, sum(`amount`) as amount
                    FROM `user_transfers` group by EXTRACT(MONTH FROM `created_at`), EXTRACT(YEAR FROM `created_at`), user_id) as tf
              ON tf.user_id = u_rs.user_id AND tf.month = u_rs.month AND tf.year = u_rs.year
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
            DROP VIEW IF EXISTS `view_statistical`;
            SQL;
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */


}
