<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\VideoRepository;
use App\Services\VideoService;
use App\Enums\EStatus;

use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNotification;
use App\Enums\ENotification;

use DB;
use Log;
use App\Models\User;
use Carbon\Carbon;


class TranscodeVideo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    private $_service;
    private $_repo;
    private $_noti = false;
    protected $signature = 'transcode:video';

    private $_query_update = '';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'update thumbnail for video from vimeo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(VideoRepository $_repo, VideoService $_service)
    {
        $this->_service = $_service;
        $this->_repo = $_repo;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            DB::beginTransaction();

            $_carbon = Carbon::now()->subDay(3)->format('Y-m-d H:i:s');

            $item = $this->_repo->fetchWhere(['transcode' => 'in_progress'])
                                ->with([ 'request' => function( $_query ){ return $_query->where('status', EStatus::APPROVED ); } , 'request.teacher' ])
                                ->where('updated_at', '>', $_carbon)
                                ->orderBy('created_at','DESC')->limit(10)->get();

            foreach($item as $_key => $_row){

                $this->_service->_video = $_row;

                // CHECK SEND NOTICATON TO ADMIN
                $_handle = $this->_query_update .= $this->_service->handle();

                if( $_handle )
                {
                    if( $_row->request->count() )
                    $this->_noti = true;

                    $this->_query_update = $this->_query_update .= $this->_service->handle();
                    continue;
                }

                // CHECK TIMEOUT VIMEO TRANSCODE
                if( $_time_out = $this->_service->timeOutTrancode() )
                {
                    $this->_query_update .=  $_time_out;
                    Log::debug(['check vimeo trancode time out', $_time_out]);
                    continue;
                }

                // CLEAR VIDEO NOT SUBMIT = 0, 'is_submit' => 1

                // if( $_clear = $this->_service->clearVideo() )
                // {
                //     $this->_query_update .=  $_clear;
                //     Log::debug(['delete video not complete', $_row->id ]);
                //     continue;
                // }

            }


            if( $this->_query_update != '' )
                DB::unprepared( $this->_query_update );

            if( $this->_noti )
                Notification::send(  User::where('role','admin')->get() , new SendNotification( (object) ENotification::UPLOADED_VIDEO() ) );

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            report( "schedule update video \n".$e );
        }
    }


}
