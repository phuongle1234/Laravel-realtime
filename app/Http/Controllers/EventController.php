<?php

namespace App\Http\Controllers;

//use Illuminate\Support\Facades\Auth;
use App\Enums\EStatus;
use Illuminate\Http\Request;
use App\Services\VerifyUserService;
use App\Events\MessageSentEvent;
use App\Repositories\RequestRepository;
use App\Models\Contact;
use App\Models\Message;
use Log;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Helper\FileManage;
use App\Models\Video;

// viemo
use App\Traits\VimeoApiTrait;
use App\Services\VimeoService;

use App\Jobs\EventLikeJobs;
use App\Models\SchoolMaster;
use App\Services\StatisticsService;
use App\Traits\StoreFileTrait;
use ErrorException;
use DB;

use function PHPUnit\Framework\throwException;

class EventController extends Controller
{
	use VimeoApiTrait, StoreFileTrait;

	private $_master_school;
	private $_statis_service;
	private $_request_repo;

	private $_viemo_service;

    public function __construct(
								VerifyUserService  $_verifyService,
								StatisticsService $_statis_service,
	                            RequestRepository $_request_repo,
								SchoolMaster $_master_school,
								VimeoService $_viemo_service
								)
    {
		 $this->_master_school = $_master_school;
    	 $this->_verifyService = $_verifyService;
		 $this->_request_repo = $_request_repo;
		 $this->_statis_service = $_statis_service;

		 $this->_viemo_service = $_viemo_service;

    }

	public function userStatistics(Request $Request){
		try {

				$_item = $this->_statis_service->totalViewByMonth([$Request->month,$Request->year])->toArray();

				$_item = collect( $_item )->mapWithKeys( function( $_val ){
																			return [$_val['day'] => (int)$_val['seconds']];
																		})->toArray();
				return response()->json($_item, 200);

		} catch (\Throwable $e) {
			DB::rollBack();
			report( $e );
			return response($e->getMessage(), 400);
		}
	}

	//linkDowload $this->_request_repo

	public function checkRequest(Request $Request){
		try {

		 	$_item = $this->_request_repo->fetchWhere([ 'id' => $Request->id ])->exists();

			if( ! $_item )
			return response( trans('common.error.expired') , 220 );
			// throw new ErrorException( trans('common.error.expired') );

			return response( trans('common.success'), 200);

		} catch (\Throwable $e) {
			// report( $e );
			return response($e->getMessage(), 400);
		}
	}

	public function linkDowload(Request $Request){

		try {

			// $_item = $this->_request_repo->fetchWhere([ 'id' => $Request->id ])->first('path');
			// $_file = explode( '/', $_item->path );
			// $_count_file = count( $_file );


			$assetPath = Storage::disk('s3')->get(  "video/". $Request->name );

			$headers = [
				'Content-Type'        => 'Content-Type: application/pdf',
				"Content-Description: File PDF",
				'Content-Disposition' => 'attachment; filename="'. $Request->name .'"',
			];

			return Response()->make( $assetPath, 200, $headers);

		} catch (\Throwable $e) {

			report( $e );
			return response($e->getMessage(), 400);
		}
	}

	public function userView(Request $Request){

		try {

			DB::beginTransaction();

				$_user = Auth::guard( 'student' )->user();
				$_user->views()->create(['video_id'=> $Request->video_id, 'request_id' => $Request->request_id , 'status' => EStatus::ACTIVE]);
				$_user->watches()->create(['video_id' => $Request->video_id, 'request_id' => $Request->request_id, 'seconds' => $Request->seconds, 'status' => EStatus::ACTIVE]);

			DB::commit();

			return response()->json( ['mess' => trans('common.success')] , 200);

		} catch (\Throwable $e) {
			DB::rollBack();
			report( $e );
			return response($e->getMessage(), 400);
		}
	}

	public function getNumberMessage(Request $_request){

		try {
			$_user = Auth::guard( $_request->role )->user();
			return response()->json( $_user->contact()->count(), 200);
		} catch (\Throwable $e) {
			report( $e );
			return response( $e, 400);
		}

	}

	public function getNumberRequestDirect(Request $_request){

		//$_user->nominationRequests()->where(['status' => EStatus::PENDING, 'is_displayed' => EStatus::IS_DISPLAYED])->count();

		try {
			$_user = Auth::guard( $_request->role )->user();
			return response()->json( $_user->nominationRequests()->where(['status' => EStatus::PENDING, 'is_displayed' => EStatus::IS_DISPLAYED])->count(), 200);
		} catch (\Throwable $e) {
			report( $e );
			return response( $e, 400);
		}

	}

	public function userLike(Request $_request){

		try {

			$_user = Auth::guard( 'student' )->user();
			//, 'status' => 'complete'
			$_item = $this->_request_repo->fetchWhere([ 'id' => $_request->id, 'status' => EStatus::COMPLETE ])
						  ->with('video')->first();

			if( !$_item )
				throw new ErrorException( trans("common.error") );

			$_vimeo_id = $_item->video->vimeo_id;
			$_video_id = $_item->video->id;
			$_status = $_request->status;

			$_user->likes()->updateOrCreate(['video_id' => $_video_id ],[ 'video_id' => $_video_id, 'status' => $_status, 'request_id' => $_request->id ]);

			dispatch( new EventLikeJobs( $_video_id, $_status, VimeoService::class) )->beforeCommit();

			//$this->like( $_vimeo_id, $_status == 'active' ? false : true );

			$_item = $_item->fresh();
			$_count = $_item->like()->count();

			return response()->json( $_count, 200 );

			//likes
		} catch (\Throwable $e) {
			report( $e );
			return response( $e, 400);
		}
	}

	public function videoToken(Request $_request){

		try {

			$_user = Auth::guard('teacher')->user();



			if(!$_user)
				throw new ErrorException( trans("common.error") );
			    $_item = $this->_viemo_service->uploadVideo($_request->name, $_request->description);

			if($_item['status']>=400)
				throw new ErrorException( trans("common.error") );

			$_item = $_item['body'];

			$_user_vimeo_id = env('VIMEO_USER_ID');
			$_player_embed_url = asset('images/transcode.svg');

			$_thumbnail = asset('images/waiting.jpg');

			$_video_id = $_user->videos()->create([
							'owner_id' => $_request->id,
							'title' => $_request->name,
							'description' => $_request->description,
							'vimeo_id' => str_replace( '/videos/','', $_item['uri'] ),
							'user_vimeo_id' => $_user_vimeo_id,
							'thumbnail' => $_thumbnail,
							'player_embed_url' => $_player_embed_url,
							'transcode' => $_item['transcode']['status']
						]);


			if( ! empty( $_request->request_value ) )
			{
				$_data_request = array_merge( $_request->request_value , ['video_id' => $_video_id->id, 'video_title' => $_request->name, 'description' => $_request->description] );
				$request_id = $_data_request['request_id']; unset( $_data_request['request_id'] );
				\App\Models\Request::find( $request_id )->update($_data_request);
			}

			return response()->json( $_item, 200 );

		} catch (\Throwable $e) {
			report( $e );
			return response( $e, 400);
		}
	}

	public function getWarningBlock(Request $request)
	{

		try {

			$_user = Auth::guard('teacher')->user();
			$_item = $_user->nominationRequests()->where(['status' => EStatus::PENDING,'is_displayed' => EStatus::IS_DISPLAYED])->with('subject')->orderBy('requests.created_at','DESC')->get();
			return view('component.teacher.home.warning_block', compact('_item') );

        } catch (\Throwable $th) {
            report( $th->getMessage() );
			return response()->json(['erorr' => $th->getMessage() ],400);
        }

	}

	//getFaculty getView


	public function getUniversity(Request $Request){
			try {
				return response()->json( $this->_master_school->university( ) , 200);
			} catch (\Throwable $e) {
				report( $e );
				return response($e->getMessage(), 400);
			}
	}

	public function getFaculty(Request $Request){

		try {

			return response()->json( $this->_master_school->faculty( $Request->id ) , 200);
		} catch (\Throwable $e) {
			report( $e );
			return response($e->getMessage(), 400);
		}
	}

	public function chat(Request $Request){
		try {

			Contact::where(['user_id' =>  $Request->user_to ,'message_id' => $Request->message_id ])->update(['seen_at' => null]);

			$_path = null;

			if($Request->path != 'null'){
				$_name_file = Carbon::now()->format('YmdHis-').rand(0,999).'.png';
				// $_path = Storage::disk('public')->putFileAs('file_chat', $Request->path, $_name_file.$Request->path->getClientOriginalExtension() );
				$_path = $this->storageUpload($Request->path, 'file_chat', $_name_file);
				// $_path = Storage::url($_path);
			}

			event( new MessageSentEvent( $Request->user_to,
										$Request->user_from,
										$Request->message_id,
										$Request->message != null ? $Request->message : '',
										$_path) );


		    return response()->json([
				'user_id' => $Request->user_from,
				'message_id' => $Request->message_id,
				'message' => $Request->message,
				'path' => $_path,
				'created_at' => Carbon::now()->format('Y-m-d H:i')
			 ],200);

		} catch (\Throwable $e) {
			report( $e );
			return response($e->getMessage(), 400);
	   }
	}

	public function getMessage(Request $request)
	{

		try {

			$_message = Message::where('id' , $request->id)->first();

			if($_message){
				Contact::where(['user_id' => $request->user_id, 'message_id' => $_message->id ])
				->update(['seen_at' => Carbon::now()->format('Y-m-d H:i') ]);
			}

			if( !( $_content = json_decode($_message->content,true) ) )
				$_content = [];

			$_count = Contact::where(['user_id' => $request->user_id])->whereNull('seen_at')->count();

			return response()->json(['message' => $_content, 'count' => $_count],200);

        } catch (\Throwable $th) {
            report( $th->getMessage() );
			return response()->json(['erorr' => $th->getMessage() ],400);
        }

	}

	public function seenMessage(Request $Request){
		try {

			Contact::where(['user_id' =>  $Request->_user_id ,'message_id' => $Request->_message_id])
					->update(['seen_at' => Carbon::now()->format('Y-m-d H:i') ]);

		    return response( trans('common.success') , 200);
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return response($e->getMessage(), 400);
	   }
	}
	//
	public function eventSeen(Request $Request){
		try {

			Contact::where(['user_id' =>  $Request->user_id ,'message_id' => $Request->message_id])
					->update(['seen_at' => Carbon::now()->format('Y-m-d H:i') ]);

			$_count = Contact::where('user_id', $Request->user_id)->whereNull('seen_at')->count();

		    return response()->json($_count,200);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return response($e->getMessage(), 400);
	   }
	}
	// change percent training
	public function percentTraining(Request $Request){
		try {
			Auth::guard($Request->role)->user()->verifyUser()->updateOrCreate(['percent_training' => $Request->percent ]);
			return response( trans('common.success') , 200);
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return response($e->getMessage(), 400);
	   }
	}

}
