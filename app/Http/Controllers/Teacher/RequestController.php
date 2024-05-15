<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\EStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use App\Repositories\RequestRepository;

use App\Enums\EUser;
use App\Enums\EPage;

use Stripe\Customer;
use Stripe\Stripe;
use Carbon\Carbon;

use Auth;
use App\Traits\VimeoApiTrait;

use App\Services\VideoService;
use App\Requests\Teacher\AcceVideoRequest;

use Illuminate\Support\Facades\Notification;
use App\Notifications\SendNotification;
use App\Enums\ENotification;

use Illuminate\Support\Facades\DB;
use ErrorException;

use App\Services\RequestService;

class RequestController extends Controller
{
	use VimeoApiTrait;

	private $_repo;

	private $_video_service;

	const URL_INDEX = "student.request.list";
	const PATH_INDEX = "pages.request.home";

    public function __construct(RequestRepository $_repo, VideoService $_video_service)
	{
		$this->_repo = $_repo;
		$this->_video_service = $_video_service;
		parent::__construct();
	}

	public function index(Request $request)
	{
		try {

			$_user = Auth::guard('teacher')->user();
			$_result = $this->_repo->fetchWhere(['subject_id' => $request->id, 'status' => EStatus::PENDING, 'is_displayed' => EStatus::IS_DISPLAYED, 'is_direct' => 0])
					 ->with(['subject','subject.tags'])
					 ->whereNotIn('user_id',$_user->list_blocked_by_user)
					 ->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
					  // ->paginate( $this->_limit );
					 ->get();

			return view('component.teacher.request.item', compact('_result'));
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return response()->json( $e ,400);
		}
	}

	//HandleRequest
	public function handle(Request $request)
	{
		try {

			$_user = Auth::guard('teacher')->user();

			$_item = $this->_repo->fetchWhere([ 'id' => $request->id ])
					 ->with('student')
					 ->whereNotIn('user_id', $_user->list_blocked_by_user )
					 ->first();

			if( ! $_item )
				throw new ErrorException( trans("common.error") );


			switch($request->status){
				case 'cancel':

					$_data = [
						// 'deadline' => Carbon::now()->addDay(2)->format('Y-m-d H:i'),
						'user_receive_id' => null,
						'is_direct' => 0
					];

					Notification::send( $_item->student , new SendNotification( (object)ENotification::NOT_ACCEPT_REQUEST( $_item->title, $_user->name ) ) );

					break;

				case 'accept':

					$_data = [
						'status' => EStatus::ACCEPTED,
						'user_receive_id' => $_user->id,
						'deadline' => EUser::getDeadLine(EStatus::ACCEPTED),
					];

					break;
			}

			$_item->update($_data);
			$_item->save();

			$_request = $_item->fresh();

			if( $_request->status == EStatus::ACCEPTED )
				RequestService::handle($_request);

			//$this->_repo->fetchWhere(['id' => $request->id])->update($_data);

			return redirect()->back()->withErrors( trans("common.success") );

		} catch (\Throwable $e) {
			report( $e );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function suggesRequest(Request $request)
	{
		try {

			$_user = Auth::guard('teacher')->user();
			$_data = $_user->suggesRequest()->with(['subject'])->get();

			return response()->json( ['list' => $_data, 'subject_data' => $_user->subject()->get() ],200);

		} catch (Throwable $e) {
			report( $e );
			return response()->json( trans("common.error") ,400);
		}
	}

	public function review(Request $request)
	{
		try {


			$item =  $this->_repo->fetchWhere(['id' => $request->id])
					 ->with(['subject','teacher', 'video', 'teacher.subject', 'tags', 'fields'])
					 ->first();

			$view = $item->status;

			if( $request->status == 'review'){

				$view = $item->status;

				if($item->status == EStatus::DENIED)
				 $view = EStatus::APPROVED;

				 if($item->status == EStatus::DELAYED && $item->video_id == null)
				 $view = EStatus::ACCEPTED;

				 if($item->status == EStatus::DELAYED && $item->video_id != null)
				 $view = EStatus::APPROVED;

				//  if($item->status == 'delayed' && $item->video_id != null)
				//  $view = 'approved';

			}
			//    return response()->json( $view,200);

			   return view("component.teacher.request.poup_review.{$view}", compact('item') );

		} catch (\Throwable $e) {
			report( $e );
			return response()->json($e->getMessage(),400);
		}
	}

	public function accepted(Request $request, $id)
	{
		try {

			$_user = Auth::guard('teacher')->user();
			$item =  $this->_repo->fetchWhere(['id' => $request->id, 'user_receive_id' => $_user->id ])
								 ->with('video')
								 ->whereNull('video_id')
			                     ->whereIn('status', [EStatus::ACCEPTED,EStatus::DELAYED])->first();

			if(!$item)
				throw new ErrorException( trans("common.error") );

			return view('pages.teacher.request.accepted_request', compact('item'));

		} catch (\Throwable $e) {
			report( $e );
			abort(403, trans("common.error.request") );
			//return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function edit(Request $request, $id)
	{
		try {

			$_user = Auth::guard('teacher')->user();

			$item =  $this->_repo->fetchWhere([ 'id' => $request->id, 'user_receive_id' => $_user->id ])
								 ->whereIn('status', [ EStatus::ACCEPTED,EStatus::DENIED,EStatus::DELAYED])
								 ->whereNotNull('video_id')
								 ->with(['subject','teacher', 'video', 'teacher.subject', 'tags', 'fields'])
								 ->first();

			if(!$item)
				throw new ErrorException( trans("common.error") );

			return view('pages.teacher.request.edit', compact('item'));

		} catch (\Throwable $e) {
			report( $e );
			abort(403, trans("common.error.request") );
			//return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function handelAccept(AcceVideoRequest $request, $id)
	{
		try {

			DB::beginTransaction();

				$_update = $request->only('video_title','description', 'subject_id', 'tag_id', 'field_id');

				if( $request->status_upload == 'create_new' ){

					$_video = $this->_video_service->storeNew();
					// $_update['video_id'] = $_video->id;
					if( $_video->path )
						$_update['path'] = $_video->path;

				}else{
					$_update['video_id'] = $request->video_id;
					$_update['path'] = $request->path_id;

					Notification::send( \App\Models\User::where('role','admin')->get() , new SendNotification( (object) ENotification::UPLOADED_VIDEO( ) ) );
				}

				$_user = Auth::guard('teacher')->user();

				$item =  $this->_repo->fetchWhere(['id' => $id, 'user_receive_id' => $_user->id ])->whereIn('status', [EStatus::ACCEPTED,EStatus::DELAYED])->first();

				if(!$item)
					throw new ErrorException( trans("common.error") );

				$_update['deadline'] = EUser::getDeadLine(EStatus::APPROVED);

				$_update['status'] = EStatus::APPROVED;

				$item->update($_update);

				// User notification to Admin


			DB::commit();

			return redirect()->route('teacher.request.accepted')->with(['message'=>trans("common.success")]);

		} catch (Throwable $e) {
			DB::rollBack();
			report( $e );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	//handleEdit

	public function handleEdit(AcceVideoRequest $request, $id)
	{
		try {

			DB::beginTransaction();

				$_update = $request->only('video_title','description', 'subject_id', 'tag_id', 'field_id');

				if( $request->status_upload == 'create_new'  ){

					$_video = $this->_video_service->storeNew();
					$_update['video_id'] = $_video->id;

					if( $_video->path )
					$_update['path'] = $_video->path;

				}else if($request->video_id) {
					$_update['video_id'] = $request->video_id;
					$_update['path'] = $request->path_id;

					if($request->path)
					$_update['path'] = $this->_video_service->storePdf();
					//Storage::disk('public')->putFileAs('video', $request->path, $request->path->getClientOriginalName() );
				}

				$_user = Auth::guard('teacher')->user();
				//->whereIn('status', [EStatus::DENIED,EStatus::DELAYED])
				$item =  $this->_repo->fetchWhere(['id' => $id, 'user_receive_id' => $_user->id])->whereNotNull('video_id')->first();

				if(!$item)
					throw new ErrorException( trans("common.error") );


				$_update['deadline'] = EUser::getDeadLine( EStatus::DENIED, str_replace('reject at:','',$item->note) );
				$_update['status'] = EStatus::APPROVED;
				$item->update($_update);

				// notification to admin

				foreach( \App\Models\User::where('role','admin')->get() as $_key => $_manager ){
					Notification::send( $_manager , new SendNotification( (object) ENotification::UPLOADED_VIDEO( ) ) );
				}

			DB::commit();

			return redirect()->route('teacher.request.accepted')->with(['message'=>trans("common.success")]);

		} catch (Throwable $e) {
			DB::rollBack();
			report( $e );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

}