<?php

namespace App\Services;

use App\Enums\EStatus;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Traits\VimeoApiTrait;
use Faker\Extension\Extension;
use DB;
use Auth;
use Illuminate\Support\Facades\Storage;

use App\Repositories\VideoRepository;
use App\Repositories\RequestRepository;
use App\Traits\StoreFileTrait;
use App\Services\VimeoService;

use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNotification;
use App\Enums\ENotification;

use ErrorException;

class VideoService
{
    use StoreFileTrait;

    private $_vimeo_service;

    private $_request_repo;
    public $_video;
    private $_request;
    private $_thumbnail;
    private $_player_embed_url;
    private $_transcode;
    private $_videoRepo;

    const TIME_OUT = 2; // hour

    public function __construct(Request $_request , VideoRepository $videoRepository, RequestRepository $_request_repo, VimeoService $_vimeo_service)
	{
		$this->_request = $_request;
        $this->_vimeo_service = $_vimeo_service;
        $this->_request_repo = $_request_repo;
        $this->_videoRepo = $videoRepository;
	}

    // clear video not submit
    public function clearVideo()
    {
        if( $this->_video->is_submit )
            return false;

        if( $this->_request_repo->fetchWhere(['video_id' => $this->_video->id ])->count() )
            return false;

            // $_now = Carbon::now();
            // $_created = Carbon::parse( $this->_video->updated_at );

            // $_status = EStatus::DENIED;

            // if(  $_now->diffInHours($_created) >= self::TIME_OUT  && $this->_video->request->count() )

        $this->_vimeo_service->deleteVideo( $this->_video->vimeo_id  );

        return " UPDATE `videos` SET `deleted_at` = NOW() WHERE `id` = '{$this->_video->id}' ;";
    }


    public function timeOutTrancode()
    {
        $_now = Carbon::now();
        $_created = Carbon::parse( $this->_video->updated_at );

        $_status = EStatus::DENIED;

        if(  $_now->diffInHours($_created) >= self::TIME_OUT  && $this->_video->request->count() )
        return $this->_video->request->map( function($val) use ($_status)
                {
                    Notification::send( $val->teacher , new SendNotification( (object) ENotification::DENIED_VIDEO_VIMEO( $val->teacher->name, $val->id, $val->title ) ) );
                    return " UPDATE `requests` SET `status` = '{$_status}', `updated_at` = NOW() WHERE `id` = '{$val->id}' ;";
                })->implode('');

        return false;
    }

    public function handle()
    {

        $this->checkVideo();

        if(  $this->_transcode != 'complete' )
            return false;

        if(  $this->_thumbnail == 'https://i.vimeocdn.com/video/default' ){

            $this->_thumbnail = asset('images/waiting.jpg');
            $this->_player_embed_url = asset('images/transcode.svg');
            $this->_transcode = 'in_progress';
        }


        return  " UPDATE `videos` SET `thumbnail` = '{$this->_thumbnail}', `player_embed_url` = '{$this->_player_embed_url}', `transcode` = '{$this->_transcode}', `updated_at` = NOW() WHERE `id` = {$this->_video->id};";

    }

    public function countAproved(){

		$item = $this->_request_repo->model
				->leftJoin( 'videos', 'videos.id', '=', 'requests.video_id')
				->where([ 'requests.status' => EStatus::APPROVED, 'videos.transcode' => 'complete' ])
				->count();

		return $item;

	}

    public function storePdf()
    {
        return $this->storageUpload( $this->_request->path,'video' );
    }

    public function storeNew()
    {

        $request = $this->_request;

        $_user = Auth::guard('teacher')->user();

        if( $request->path_id ){
            $_pdf = $request->path_id;
        }else if( $request->path ){
            // $_pdf = Storage::disk('public')->putFileAs('video', $request->path, $request->path->getClientOriginalName() );
            $_pdf = $this->storageUpload( $request->path,'video' );
        }

        if( $request->video_title ){
            $_title = $request->video_title;
        }else{
            $_title = $request->title;
        }

        // $vimeo = $this->editVideo($request->video_id, $_title, $request->description);
        // $vimeo = $vimeo['body'];

        //vimeo_id

        return    $_user->videos()->updateOrCreate(
                    [
                      'owner_id' => $_user->id,
                      'vimeo_id' => $request->video_id
                    ],
                    [
                        // 'vimeo_id' => $request->video_id,
                        // 'title' =>  $_title,
                        // 'description' =>  $request->description,
                        // 'thumbnail' => $vimeo['pictures']['base_link'],
                        // 'player_embed_url' => $vimeo['player_embed_url'],
                        // 'transcode' => $vimeo['transcode']['status'],
                        'path' => isset($_pdf) ? $_pdf : null,
                        'is_submit' => 1
                    ]);
    }

    private function checkVideo()
    {
       $_item = $this->_vimeo_service->getVideoById( $this->_video->vimeo_id )['body'];

       if(!$_item)
            throw new ErrorException( "Invalid viemo id: {$this->_video->vimeo_id}" );

       if( isset( $_item['pictures']['base_link'] ) )
            $this->_thumbnail = $_item['pictures']['base_link'];

       if( isset( $_item['player_embed_url'] ) )
            $this->_player_embed_url = $_item['player_embed_url'];

        if( isset( $_item['transcode']['status'] ) )
            $this->_transcode = $_item['transcode']['status'];
    }

    public function getApproved(){
        return $this->_videoRepo->getApproved();
    }

}
