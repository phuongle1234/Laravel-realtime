<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use App\Repositories\RequestRepository;

use App\Enums\EUser;

use Stripe\Customer;
use Stripe\Stripe;
use Carbon\Carbon;
use App\Requests\Teacher\VideoRequest;

use App\Jobs\UploadVideoJob;
use App\Repositories\VideoRepository;
use App\Services\VideoService;

use App\Services\VimeoService;

use DB;
use Auth;


class VideoController extends Controller
{

	private $_vimeo_servie;

	private $_repo;
	private $_service;


	const URL_INDEX = "student.request.list";
	const PATH_INDEX = "pages.request.home";

    public function __construct(VideoRepository $_repo, VideoService $_service, VimeoService $_vimeo_servie)
	{
		$this->_vimeo_servie = $_vimeo_servie;
		$this->_repo = $_repo;
		$this->_service = $_service;
		parent::__construct();
	}

	public function index(Request $request)
	{
		try {


			$_user = Auth::guard('teacher')->user();

			$item = $this->_repo->fetchWhere(['owner_id' => $_user->id])
								->withCount('request','views')
								->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
								->paginate( $this->_limit );

			return view('pages.teacher.video.index', compact('item') );

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return response()->json( $e ,400);;
		}
	}

	public function store(VideoRequest $request)
	{

		try {

			$this->_service->storeNew();
			return redirect()->back()->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function delete(Request $request){

		try {
			DB::beginTransaction();

				$_user = Auth::guard('teacher')->user();

				$item = $this->_repo->fetchWhere(['owner_id' => $_user->id, 'id' => $request->id ])->withCount('request')->first();

				if(!$item)
					return redirect()->back()->withErrors( trans("common.error") );
					//throw new ErrorException( trans("common.error") );

				if( $item->request_count )
					return redirect()->back()->withErrors( trans("common.error") );

				Storage::delete( 'public/'.$item->path );

				$this->_vimeo_servie->deleteVideo( $item->vimeo_id );

				$item->delete();

			DB::commit();

			return redirect()->back()->with(['message'=>trans("common.success")]);

		} catch (Throwable $e) {
			DB::rollBack();
			report( $e );
			return redirect()->back()->withErrors( trans($e->getMessage()) );
		}
	}

	public function update(Request $request){
		try {

			DB::beginTransaction();

				$_update = $request->only('title','description');

				$_user = Auth::guard('teacher')->user();
				$item = $this->_repo->fetchWhere(['owner_id' => $_user->id, 'id' => $request->id ])->with('request')->first();

				if(!$item)
					throw new ErrorException( trans("common.error") );

				$_video_id = $item->vimeo_id;

				if($request->path){
					Storage::delete( 'public/'.$item->path );
					$_update['path'] = Storage::disk('public')->putFileAs('video', $request->path, $request->path->getClientOriginalName() );
				}


				if($request->video_id){

					$this->_vimeo_servie->deleteVideo( $_video_id );
					$item->delete();

					$_request = $item->request;

					$item = $this->_repo->fetchWhere(['owner_id' => $_user->id, 'vimeo_id' => $request->video_id ])->first();

					foreach($_request as $_key => $_val){
						$_val->video_id = $item->id;
						$_val->save();
					}

					$_video_id = $request->video_id;
				}

				// $_update['vimeo_id'] = $_video_id;

				$vimeo = $this->_vimeo_servie->editVideo( $_video_id, $request->title, $request->description)['body'];

				if( $vimeo['transcode']['status'] == 'complete'  ){
					$_update['thumbnail'] = $vimeo['pictures']['base_link'];
					$_update['player_embed_url'] = $vimeo['player_embed_url'];
					$_update['transcode'] = $vimeo['transcode']['status'];
				}

				$item->update($_update);

			DB::commit();

			return redirect()->back()->with(['message'=>trans("common.success")]);

		} catch (Throwable $e) {
			DB::rollBack();
			report( $e );
			return redirect()->back()->withErrors( trans($e->getMessage()) );
		}
	}

}