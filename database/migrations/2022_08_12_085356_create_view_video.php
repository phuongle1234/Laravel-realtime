<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Schema;
use Staudenmeir\LaravelMigrationViews\Facades\Schema;

class CreateViewVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::createView('view_videos',$this->createView());
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
           select `rq`.`id` AS `id`,`rq`.`title` AS `title`,`rq`.`content` AS `content`,`rq`.`tag_id` AS `tag_id`,`rq`.`field_id` AS `field_id`,`tag`.`name` AS `tag_name`,`fied`.`name` AS `fied_name`,`rq`.`subject_id` AS `subject_id`,`us`.`university_Code` AS `university_Code`,`rq`.`user_receive_id` AS `user_receive_id`,`rq`.`deadline` AS `deadline`,`rq`.`created_at` AS `created_at`,`vd`.`thumbnail` AS `thumbnail`,`wt`.`watch` AS `watch`,`rt`.`rating` AS `rating`,`sb`.`name` AS `subject_name`,`sb`.`icon` AS `icon`,`vd`.`active` AS `active`,`rq`.`deleted_at` AS `deleted_at` from (((((((`edutoss`.`requests` `rq` left join `edutoss`.`videos` `vd` on(`rq`.`video_id` = `vd`.`id`)) left join (select `edutoss`.`user_watches`.`request_id` AS `request_id`,sum(`edutoss`.`user_watches`.`seconds`) AS `watch` from `edutoss`.`user_watches` group by `edutoss`.`user_watches`.`request_id`) `wt` on(`wt`.`request_id` = `rq`.`id`)) left join (select `edutoss`.`user_ratings`.`request_id` AS `request_id`,sum(`edutoss`.`user_ratings`.`evaluation`) AS `rating` from `edutoss`.`user_ratings` group by `edutoss`.`user_ratings`.`request_id`) `rt` on(`rt`.`request_id` = `rq`.`id`)) left join `edutoss`.`tags` `tag` on(`tag`.`id` = `rq`.`tag_id` and `tag`.`tag_type` = 'field')) left join `edutoss`.`tags` `fied` on(`fied`.`id` = `rq`.`field_id` and `fied`.`tag_type` = 'difficult')) left join `edutoss`.`subjects` `sb` on(`sb`.`id` = `rq`.`subject_id`)) left join `edutoss`.`users` `us` on(`us`.`id` = `rq`.`user_receive_id`)) where `rq`.`status` = 'complete' and `rq`.`deleted_at` is null
        SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
            DROP VIEW IF EXISTS `view_videos`;
            SQL;
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */


}
