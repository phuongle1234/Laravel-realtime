<?php

namespace App\Http\Controllers\Student;

use App\Enums\EStatus;
use App\Enums\EStripe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\RequestRepository;
use App\Services\RequestService;

use App\Requests\Student\HandleRequest;
use Illuminate\Support\Facades\DB;

use App\Enums\EUser;

use Stripe\Customer;
use Stripe\Stripe;
use Carbon\Carbon;
use Mail;
use Image;
use Auth;
use Hash;
use Exception;
use ErrorException;

use App\Traits\StoreFileTrait;


class RequestController extends Controller
{
	use StoreFileTrait;
	private $_repo;

	private $_order_by;
	private $_sevice;

	const URL_INDEX = "student.request.list";
	const PATH_INDEX = "pages.request.home";

    public function __construct(RequestRepository $_repo, RequestService $_sevice)
	{
		$this->_repo = $_repo;
		$this->_sevice = $_sevice;
		parent::__construct();
	}

	public function index(Request $request)
	{
		try {

			return view(self::PATH_INDEX.'.index');

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}
	//HandleRequest

	public function handle(HandleRequest $request)
	{
		try {
			DB::beginTransaction();

			$_user = Auth::guard('student')->user();

			$_file_path = [];

			if($request->path)
			{

				foreach($request->path as $key => $_file){
					array_push( $_file_path, $this->storageUpload($_file, 'request', Carbon::now()->format('YmdHis').$key . ".png" )  );
				}

			}

			if( empty($_file_path) )
				array_push( $_file_path, asset('images/no_img.png')  );

			// ,'private'

				$_store = $request->only('title','content','subject_id');

				if( $_user->stripe()->first()->plan_id != EStripe::PREMIUM_PLAN_ID )
				{
					$_tickets = $_user->tickets()->create(['amount'=> -1, 'status' => EStatus::ACTIVE, 'note' => 'subtract points from request' ]);
					$_store['ticket_id'] = $_tickets->id;
				}

				$_store['file_path'] = json_encode($_file_path);

				$_store['status'] = EStatus::PENDING;
				$_store['deadline'] = EUser::getDeadLine( $_store['status'] );


				if( $request->user_receive_id ){
					$_store['is_direct'] = 1;
					$_store['user_receive_id'] = $request->user_receive_id;
				}

				$_requrst = $_user->request()->create($_store);

			DB::commit();

			return redirect()->route(self::URL_INDEX);

		} catch (Throwable $e) {

			DB::rollBack();
			report( $e );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function review(Request $request)
	{
		try {

			$item =  $this->_repo->fetchWhere(['id' => $request->id])
					 ->withCount('like')
					 ->with(['subject','teacher', 'teacher.settings', 'video', 'teacher.subject'])
					 ->first();

			if( $request->review_poup )
				return view("component.pop_up.review_complete", compact('item') );

			switch($item->status){
				case EStatus::COMPLETE:
					 $_status = $item->status; break;
				case EStatus::PASS:
					$_status = EStatus::APPROVED; break;
				case EStatus::PENDING:
					$_status = EStatus::PENDING; break;
				default: $_status = EStatus::ACCEPTED;
			}


			return view("component.pop_up.review_{$_status}", compact('item') );

		} catch (\Throwable $e) {

			report( $e );
			return response()->json($e->getMessage(),400);
		}
	}
	//confirm
	public function confirm(Request $request)
	{
		try {

			DB::beginTransaction();

			$_user = Auth::guard('student')->user();

			$_item =  $this->_repo->fetchWhere(['id' => $request->request_id, 'user_id' => $_user->id, 'status' => EStatus::PASS ])->first();

			if( ! $_item )
				throw new ErrorException( trans("common.error") );

			$this->_sevice->handleComplete($_item);

			\App\Models\UserRating::create([
											'user_id' => $_item->user_receive_id,
											'reviewer_id' => $_user->id,
											'evaluation' => (float)($request->rating/2),
											'request_id' => $_item->id
										]);
			DB::commit();

			return redirect()->back()->with(['message'=>trans("common.success")]);

		} catch (Throwable $e) {
			DB::rollBack();
			report( $e );
			return response()->json($e->getMessage(),400);
		}
	}

}
