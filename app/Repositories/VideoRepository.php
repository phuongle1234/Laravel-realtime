<?php

namespace App\Repositories;


use App\Models\Video;
use App\Enums\EStatus;
use DB;
use Carbon\Carbon;

class VideoRepository extends BaseRepository
{
	public $model;

	public function __construct(Video $model)
  {
        parent::__construct($model);
  }

  public function getApproved(){
    return $this->model->has('request')->get();
  }

  public function feachView( $from_date, $to_date, $key_work ){

    $this->model->prefix = 'admin-video-views';

    $_between = [ $from_date, $to_date ];

    return $this->model
                ->Search( [ 'date-time' => $_between, 'key_work' => $key_work ?? null  ] )
                ->whereHas( 'request',  function( $_query ){  return $_query->where('status', EStatus::COMPLETE ); })
                ->join( 'users', function( $_query )use( $_between )
                        {
                          return $_query->on('users.id' , '=' , 'videos.owner_id')
                                ->whereNull( 'users.deleted_at');
                        })
                ->leftJoin( DB::raw(
                          "(
                            SELECT
                              COUNT(id) as views, id, video_id
                            FROM
                              `user_views`
                            WHERE
                                CAST( `created_at` AS DATE) between '{$from_date}'  AND '{$to_date}'
                              AND
                                `status` = '".EStatus::ACTIVE."'
                            GROUP BY
                              `video_id`
                          ) AS user_views"
                          ),
                      'user_views.video_id', '=',	'videos.id')

                      ->leftJoin( DB::raw(
                              "(
                                SELECT
                                  SUM(seconds) as watches, id, video_id
                                FROM
                                  `user_watches`
                                WHERE
                                    CAST( `created_at` AS DATE) between '{$from_date}' AND  '{$to_date}'
                                  AND
                                    `status` = '".EStatus::ACTIVE."'
                                GROUP BY
                                  `video_id`
                              ) AS user_watches"
                              ),
                          'user_watches.video_id', '=',	'videos.id')

                    ->leftJoin( DB::raw(
                          "(
                            SELECT
                              COUNT(id) as likes, id, video_id
                            FROM
                              `user_likes`
                            WHERE
                                CAST( `created_at` AS DATE) between '{$from_date}' AND '{$to_date}'
                              AND
                                `status` = '".EStatus::ACTIVE."'
                            GROUP BY
                              `video_id`
                          ) AS user_likes"
                          ),
                        'user_likes.video_id', '=',	'videos.id');
  }

}
