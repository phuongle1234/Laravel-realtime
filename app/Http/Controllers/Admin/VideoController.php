<?php

namespace App\Http\Controllers\admin;

use App\Enums\EStatus;
use App\Traits\VimeoApiTrait;
use App\Http\Controllers\Controller;
use App\Services\VideoService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;


use Illuminate\Http\Request;
use App\Repositories\RequestRepository;
use App\Enums\EUser;

use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNotification;
use App\Enums\ENotification;
use App\Repositories\VideoRepository;
use Carbon\Carbon;

use App\Models\ViewVideo;

use App\Exports\FileExport;
use App\Exports\ViewsExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Auth;
use ErrorException;
use App\Requests\Admin\VideoRequest;
use Predis\Command\Redis\DUMP;

class VideoController extends Controller
{
    use VimeoApiTrait;
	private $_repo;
	private $_order_by;
	private $_videoRepo;

	private $_view_video;

	const URL_INDEX = "admin.video_management.list_approval";
	const PATH_INDEX = "pages.admin.video_management";
	const _URL ="admin.video_management";

	public function __construct(VideoService $videoService, VideoRepository $videoRepo, RequestRepository $_request_repo, ViewVideo $_view_video)
	{
		$this->_request_repo = $_request_repo;
		$this->videoService = $videoService;
		$this->_videoRepo = $videoRepo;
		$this->_view_video = $_view_video;
		parent::__construct();
	}


	public function list(Request $request){

		$_user = Auth::guard('admin')->user();
		DB::table('notifications')->where([ 'notifiable_id' => $_user->id, 'notifiable_type' => 'App\Models\User' ])->delete();

		$item = $this->_request_repo->model
				->leftJoin( 'videos', 'videos.id', '=', 'requests.video_id')
				->with('teacher')
				->where( [ 'requests.status' => EStatus::APPROVED , 'videos.transcode' => 'complete' ])
				->select( 'requests.id', 'user_receive_id', 'video_title', 'requests.created_at', 'thumbnail' , 'requests.video_id')
				->orderBy( 'requests.'.$this->_order_by_default[0], $this->_order_by_default[1] )
				->paginate( $this->_limit );

		return view( self::PATH_INDEX.'.list_waiting_approval', compact('item') );

	}

	public function listAll(Request $request){

		$items = $this->_videoRepo->model
			->with('user','request')
			->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
			->whereHas('user', function($query){  return $query->whereNull('deleted_at');  })
			->search($request->only('teacher_name','title'))
			->paginate( $this->_limit );

		return view(self::PATH_INDEX.'.list',compact('items'));
	}

	public function destroy(Request $request)
	{
		try {

			$item = $this->_videoRepo->softDeleted($request->id);
			return redirect()->route(self::_URL.'.list')->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function detail($id)
	{
		try {
			$item = $this->_videoRepo->fetch($id);
			return view(self::PATH_INDEX.'.detail',compact('item'));
		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}


	public function update(VideoRequest $request, $id)
	{
		try {

			$item = $this->_videoRepo->fetchWhere([ 'id' => $id ])
									 ->with(['request' => function( $_query ){  return $_query->where('status', EStatus::APPROVED );  } ])
									 ->first();

			if( !$item )
			return redirect()->back()->withErrors( trans("common.error") );

			$this->editVideo( $item->vimeo_id, $request->title, $request->description );

			// update request
			foreach( $item->request as $key => $_val)
			{
				$_val->update(['video_title' => $request->title, 'description' => $request->description]);
			}

			$item->update( $request->only( 'title', 'description' ) );

			return redirect()->back()->with(["message" => trans("common.success") ]);

		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}


    public function exportCsvView(Request $request){

        $from_date = $request->from_date ? $request->from_date : date('Y/m/01');
		$to_date = $request->_to_date ? $request->_to_date : date('Y/m/d');

		// name,
		// 			sum(views) as views,
		// 			sum(watch) as watchs,
		// 			sum(likes) as likes

		$_data = $this->_videoRepo->feachView( $from_date, $to_date, $request->key_work  )
									->selectRaw('users.name, sum(user_views.views) as views, sum(user_watches.watches) as watchs, sum(user_likes.likes) as likes ')
									->groupBy('videos.owner_id')
									->get();

        return Excel::download(new ViewsExport( $_data, $from_date ."～". $to_date ), "export_video_views.csv" );

        //return json_encode($response);
    }



	public function views(Request $request)
	{
		// $this->_videoRepo

		if( $request->order_by )
		{
			$request->order_by = explode("|", $request->order_by);
			$this->_order_by_default = $request->order_by;
		}

		$from_date = $request->from_date ? $request->from_date : date('Y/m/01');
		$to_date = $request->to_date ? $request->to_date : date('Y/m/d');

		$items = $this->_videoRepo->feachView( $from_date, $to_date, $request->key_work  )
									->select( 'videos.id', 'videos.title' , 'videos.description', 'videos.active', 'videos.owner_id', 'users.name', 'videos.created_at', 'videos.thumbnail', 'user_views.views', 'user_watches.watches', 'user_likes.likes' )
									->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
									->paginate( $this->_limit );

		return view(self::PATH_INDEX.'.list_views', compact('items','from_date','to_date') );
		//compact('items','from_date','to_date')

	}

	public function active(Request $request) {
		try{
			$this->_videoRepo->fetch($request->id)->update(['active'=>$request->actNum]);
			return  redirect()->route( self::_URL.'.list' )->with(['message'=>trans("common.success")]);

		} catch(Throwable $e){
			return redirect()->back()->withErrors( trans("common.error") );

		}

	}
	public function review(Request $request)
	{
		try {

			$item =  $this->_request_repo->fetchWhere(['id' => $request->id])
					 ->with(['subject','teacher', 'video', 'teacher.subject', 'tags', 'fields'])
					 ->first();

			return view("component.admin.request.{$item->status}", compact('item') );

		} catch (\Throwable $e) {
			report( $e );
			return response()->json($e->getMessage(),400);
		}
	}

	public function denied(Request $request)
	{
		try {

			$item =  $this->_request_repo->fetchWhere(['id' => $request->id, 'status' => EStatus::APPROVED ])->with('teacher')->first();

			if(!$item)
				throw new ErrorException( trans("common.error") );

			$_user = $item->teacher;

			Notification::send( $_user, new SendNotification( (object) ENotification::DENIED_VIDEO($item->title, $item->id, $request->content) ) );

			$item->note = 'reject at:'.Carbon::now()->format('Y-m-d H:i');
			$item->status = EStatus::DENIED;
			$item->save();

			return  redirect()->route( self::URL_INDEX )->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e );
			return response()->json($e->getMessage(),400);
		}
	}
	//
	public function pass(Request $request)
	{
		try {

			$item =  $this->_request_repo->fetchWhere(['id' => $request->id, 'status' => EStatus::APPROVED ])->with(['teacher','student'])->first();

			if(!$item)
				throw new ErrorException( trans("common.error") );

			 $item->deadline =  EUser::getDeadLine('pass');
			 $item->status = EStatus::PASS;
			 $item->save();

			//  Notification::send( $item->student , new SendNotification( (object) ENotification::PASS_VIDEO_TO_STUDENT( $item->name ) ) );

			return  redirect()->route( self::URL_INDEX )->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e );
			return response()->json($e->getMessage(),400);
		}
	}

}