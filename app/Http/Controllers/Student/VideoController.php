<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\RequestRepository;
use App\Services\RequestService;
use App\Repositories\VideoRepository;
use App\Models\ViewVideo;
use Illuminate\Support\Facades\DB;
use App\Enums\EUser;

use Carbon\Carbon;
use Auth;
use Exception;

class VideoController extends Controller
{
	private $_repo;


	private $_sevice;
	private $_video_filter;

	const URL_INDEX = "student.video.list";
	const PATH_INDEX = "pages.student.video";

    public function __construct(VideoRepository $_repo, RequestRepository $_request_repo, ViewVideo $_video_filter)
	{
		$this->_repo = $_repo;
		$this->_request_repo = $_request_repo;
		$this->_video_filter = $_video_filter;

	}

	public function index(Request $request)
	{
		try {

			$_user =  Auth::guard('student')->user();

			$this->_video_filter->prefix = 'student-reuqest-video';

			$_item = $this->_video_filter
					->search( $request->only('university_Code','key_work','subject_id','subject_id', 'tag_id', 'field_id') )
					->select('id','vd_title as title', 'watch', 'views', 'thumbnail','deadline','icon','subject_name','tag_name', 'vd_created_at', 'updated_at')
					->WhereNotIn('user_receive_id', $_user->list_block )
					->where('active',1)
					->orderBy( $request->sort ?? 'deadline' , 'DESC' )->paginate(20);

			return response()->json( $_item ,200);
			//user
		} catch (Throwable $e) {
			report( $e );
			return response()->json( trans("common.error") ,400);
		}
	}



}
