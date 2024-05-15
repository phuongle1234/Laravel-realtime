<?php

namespace App\Http\Controllers\Student;

use App\Enums\EStatus;
use Illuminate\Http\Request;
//use App\Traits\VimeoApiTrait;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Enums\EUser;
use App\Requests\AdmitManageRequest;
use App\Requests\Student\ChangeRegistRequest;
use Illuminate\Support\Facades\Crypt;

use App\Models\Subject;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class TeacherController extends Controller
{
	private $_userRepo;

	const URL_INDEX = "student.teacher";
	const PATH_INDEX = "pages.student.teacher_list";

    public function __construct(UserRepository $userRepo)
	{
		$this->_userRepo = $userRepo;
		$this->_userRepo->model->prefix = 'student-teacher-list';
		parent::__construct();
	}

	public function index(Request $request)
	{

		try {

			$_user = Auth::guard('student')->user();

			$this->_userRepo->model->prefix = 'student-teacher';

			$item = $this->_userRepo->fetchWhere(['role' => EUser::TEACHER, 'status' => EUser::STATUS_ACTIVE ])
					->with(['subject','university'])
					->whereNotIn('id', $_user->list_block)
					->search( $request->only('subject_id','university_Code') )
					->whereDoesntHave('settings', function ($query){ $query->where("accept_directly", 0);  })
					->select('name','id','university_Code', 'avatar', 'seen_at')
					//->orderBy( 'seen_at' , $this->_order_by_default[1] )
					// ->paginate( $this->_limit )
					->get();

			$item->append(['rating','people_rating']);

			$item = $item->sortByDesc( function( $item ){ return ($item->online ? 20 : 1) + ( $item->rating ?? 1 );  });

			 return response()->json( $item->values()->all() ,200);

			// return view(self::PATH_INDEX.'.index',compact('item'));

		} catch (\Throwable $e) {
			report( $e );
			return response()->json($e->getMessage(),400);
			// return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	// 過去に高評価を付けた先生

	public function teacherRequest(Request $request)
	{

		try {

			$_user = Auth::guard('student')->user();

			$item = $this->_userRepo
					->fetchWhere([
						'role' => EUser::TEACHER,
						'users.status' => EUser::STATUS_ACTIVE ,
						'requests.status' => EStatus::COMPLETE,
						'requests.user_id' =>  $_user->id
						// 'requests.subject_id' =>  $request->subject_id
					])
					->join('requests','users.id', '=', 'requests.user_receive_id')
					->with(['subject','university'])
					->whereNotIn('users.id', $_user->list_block)
					// ->search( $request->only('subject_id','university_Code') )
					// ->select('name','users.id','requests.id as request_id', 'users.status as user_status')
					->whereHas('subject', function ($query) use($request) { $query->where("subjects.id", $request->subject_id);  })
					->whereDoesntHave('settings', function ($query){ $query->where("accept_directly", 0);  })
					->select('users.name','users.id','users.university_Code', 'users.avatar', 'users.seen_at')
					->groupBy('requests.user_receive_id')
					->get();

			$item->append(['rating','people_rating']);

			$item = $item->sortByDesc( function( $item ){ return ($item->online ? 20 : 1) + ( $item->rating ?? 1 );  });

			 return response()->json( $item->values()->all() ,200);
			 //return response()->json($item->all(),200);

			// return view(self::PATH_INDEX.'.index',compact('item'));

		} catch (\Throwable $e) {
			report( $e );
			return response()->json($e->getMessage(),400);

		}
	}

	public function info(Request $request, $id)
	{

			$_user = Auth::guard('student')->user();

			$_info = $this->_userRepo
					->fetchWhere([ 'role' => EUser::TEACHER, 'status' => EUser::STATUS_ACTIVE, 'id' => $id ])
					->with(['subject','university','Ratings', 'university'])
					// [ 'orderRequest' => function( $query ){  return $query->where('status', EStatus::COMPLETE );  } ]
					->withCount('videos','liked','nominationRequests')
					->whereNotIn('id', $_user->list_block)
					->first();

			if( !$_info )
			return abort(403, trans('common.error.teacher_info') );

			$_video = \App\Models\ViewVideo::where([ 'user_receive_id' => $_info->id, 'active' => 1 ])
							// ->with( 'video', 'subject', 'tags')
							->orderBy('vd_created_at','DESC')->get();

			$_subject = $_video->groupBy('subject_name')->keys();

			return view( 'pages.student.teacher_list.info' , compact('_info', '_video', '_subject') );
	}

}
