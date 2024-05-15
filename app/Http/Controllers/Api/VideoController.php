<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use App\Repositories\RequestRepository;
use App\Services\RequestService;

use Illuminate\Support\Facades\DB;
use App\Enums\EStatus;
use App\Enums\EUser;

use Carbon\Carbon;
use Exception;
use ErrorException;

class VideoController extends Controller
{
	private $_repo;


	private $_sevice;
	private $_video_filter;

	const URL_INDEX = "student.video.list";
	const PATH_INDEX = "pages.student.video";

    public function __construct( RequestRepository $_request_repo )
	{
		$this->_request_repo = $_request_repo;
		parent::__construct();
	}

	public function index(Request $request)
	{
		try {

			$_item =  $this->_request_repo->fetchWhere(['video_id' => $request->id, 'status' => EStatus::COMPLETE ])
									->whereHas('video', function( $_query ){  return $_query->where('active', 1);   } )
									->select('video_id as id', 'video_title as title', 'video_id', 'user_receive_id', 'subject_id', 'field_id', 'tag_id', 'deadline')
									->withCount('like')
									->with(['subject', 'fields', 'tags',
											'teacher' => function( $_query ){ $_query->select('id', 'name', 'avatar', 'university_Code', 'seen_at'); }, 'teacher.subject',
											'teacher.university',
											'video' => function( $_query ){ $_query->select( 'id', 'title', 'description', 'thumbnail', 'player_embed_url', 'created_at'); }
											])
									->first();

			if( !$_item )
			return response()->json( "param not corret" ,400);

			$_item->teacher->append(['rating','people_rating']);

			return response()->json( $_item ,200);

		} catch (\Throwable $e) {
			report( [ 'api get video', $e ] );
			return response()->json( trans("common.error") , 400 );
		}
	}

	public function list(Request $request)
	{
		try {

			$_item =  $this->_request_repo->fetchWhere(['tag_id' => $request->id, 'status' => EStatus::COMPLETE ])
									->whereHas('video', function( $_query ){  return $_query->where('active', 1);   } )
									->select('video_id as id', 'video_title as title', 'deadline', 'subject_id', 'tag_id')
									->with( 'subject', 'tags' )
									->paginate( $this->_limit );

			if( !$_item )
			return response()->json( "param not corret" ,400);

			return response()->json( $_item ,200);

		} catch (\Throwable $e) {
			report( [ 'api get video', $e ] );
			return response()->json( trans("common.error") , 400 );
		}
	}

}
