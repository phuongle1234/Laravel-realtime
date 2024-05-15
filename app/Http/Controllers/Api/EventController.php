<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\Tag;
use App\Enums\EStatus;
use App\Enums\EUser;

use Carbon\Carbon;
use Exception;
use ErrorException;

class EventController extends Controller
{
	private $_repo;


	private $_sevice;
	private $_video_filter;

	const URL_INDEX = "student.video.list";
	const PATH_INDEX = "pages.student.video";

    public function __construct( )
	{
		// $headers = apache_request_headers();

		// if( $headers['Authentication'] != env('KEY_API') )
		// return response()->json( "param not corret" ,400);
	}

	public function subject(Request $request)
	{
		try {

			$_item = Subject::where('status',1)->get();

			if( !$_item )
			return response()->json( "param not corret" ,400);

			return response()->json( $_item ,200);

		} catch (\Throwable $e) {
			report( [ 'api get subject', $e ] );
			return response()->json( trans("common.error") , 400 );
		}
	}

	public function field(Request $request)
	{
		try {

			$_item = Tag::where([ 'subject_id' => $request->id, 'tag_type' => 'field', 'active' => 1 ])
						 ->with(['subject' => function( $_query ){ return $_query->select('id', 'name'); } ])
						 ->get();

			if( !$_item )
			return response()->json( "param not corret" ,400);

			return response()->json( $_item ,200);

		} catch (\Throwable $e) {
			report( [ 'api get field', $e ] );
			return response()->json( trans("common.error") , 400 );
		}
	}

	// BREABRUM
	public function subjectInfo(Request $request)
	{
		try {

			$_item = Subject::where('id', $request->id )->first();

			if( !$_item )
			return response()->json( "param not corret" ,400);

			return response()->json( $_item ,200);

		} catch (\Throwable $e) {
			report( [ 'api get subject', $e ] );
			return response()->json( trans("common.error") , 400 );
		}
	}

	public function fieldInfo(Request $request)
	{
		try {

			$_item = Tag::where('id', $request->id )
							->with('subject')
							->first();

			if( !$_item )
			return response()->json( "param not corret" ,400);

			return response()->json( $_item ,200);

		} catch (\Throwable $e) {
			report( [ 'api get subject', $e ] );
			return response()->json( trans("common.error") , 400 );
		}
	}

}
