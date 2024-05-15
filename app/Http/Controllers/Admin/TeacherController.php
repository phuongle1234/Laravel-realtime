<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EStatus;
use App\Events\SendMailActivatedTeacherAccount;
use App\Events\SendMailRemoveAccountEvent;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Crypt;
use App\Enums\EUser;
use App\Models\User;

use App\Models\VerifyUser;
use Exception;
use App\Requests\Admin\EditProfileTeacherRequest;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
	private $_userRepo;

	const URL_INDEX = "admin.teacherManagement.list";
	const PATH_INDEX = "pages.admin.teacher_management";

    public function __construct(UserRepository $userRepo)
	{
		$this->_userRepo = $userRepo;
		$this->_userRepo->model->prefix = 'admin-teacher';
		parent::__construct();
	}

	public function index(Request $request)
	{
		try {

			$item = $this->_userRepo->fetchWhere(['role' => EUser::TEACHER ])
									->with('subject')
									//->WithCount('orderRequest')
									->withCount([
													'orderRequest' => function($query) { $query->where('status',EStatus::COMPLETE);},
													'videos'
												])
									//->withCount('nominationRequests')
									//->whereNotIn('status',[ EUser::STATUS_DELETED ])
									// ->whereHas('verifyUser', function ($query) { $query->where('unlock','=',"1" );  })

									->search($request->only('code','name','email','subject_id', 'eSign_status'))
									->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
									->paginate( $this->_limit );

			return view(self::PATH_INDEX.'.list',compact('item'));

		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function countApprove()
	{
		try {

			return $this->_userRepo->fetchWhere(['role' => EUser::TEACHER, 'status' => Euser::STATUS_PENDING])->count();
									// ->whereHas('verifyUser', function ($query) { $query->where('unlock','=',"0" );  });
			// return $item->count();

		} catch (\Throwable $e) {
			report( $e->getMessage() );
		}
	}

	public function indexApprove(Request $request)
	{
		try {

			$item = $this->_userRepo->fetchWhere(['role' => EUser::TEACHER, 'status' => EUser::STATUS_PENDING ])
									->search($request->only('name','email','approved_admin', 'percent_training'))
									->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
									->paginate( $this->_limit );

			return view(self::PATH_INDEX.'.list_approve',compact('item'));

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function show(Request $request, $id)
	{
		try {

			$item = $this->_userRepo->fetchWhere([ 'id' => $id, 'role' => EUser::TEACHER])
					->with('subject')
					->withCount('videos','nominationRequests')->first();
			return view(self::PATH_INDEX.'.edit',compact('item'));
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function showApprove(Request $request, $id)
	{
		try {

			$item = $this->_userRepo->fetchWhere([ 'id' => $id, 'role' => EUser::TEACHER])
					->with('subject')
					->withCount('videos','nominationRequests')->first();

			return view(self::PATH_INDEX.'.edit_aprove',compact('item'));
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	//update Account not yet aprove
	public function updateAccount(EditProfileTeacherRequest $request, $id)
	{
		try {
			DB::beginTransaction();

				$item = $this->_userRepo->fetchWhere([ 'id' => $id, 'role' => EUser::TEACHER])->first();

				$item->update($request->only('name','kana','birthday','sex', 'edu_status', 'faculty_code', 'university_Code', 'approved_admin' ));

				$_subject = collect($request->subject)->map( function($val)use($item) { return ['subject_id' => $val, 'user_id' => $item->id, 'created_at' => Carbon::now() ];  } )->toArray();

				$_user_subject = $item->userSubject();
				$_user_subject->delete();
				$_user_subject->insert($_subject);
				//$item->userSubject()->updateOrCreate(['user_id','subject_id'],$_subject);

				// $item->verifyUser()->update( $request->only('approved_admin') );

			DB::commit();
			return redirect()->route( 'admin.teacherManagement.list_approve_status' )->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {

			DB::rollBack();
			report( $e );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function update(Request $request, $id)
	{
		try {
			DB::beginTransaction();
				$item = $this->_userRepo->fetchWhere([ 'id' => $id, 'role' => EUser::TEACHER])->first();
				$item->update($request->only('email','name','kana','birthday','sex','status','university_Code','faculty_code','edu_status'));

				$_subject = collect($request->subject)->map( function($val)use($item) { return ['subject_id' => $val, 'user_id' => $item->id, 'created_at' => Carbon::now() ];  } )->toArray();

				$_user_subject = $item->userSubject();
				$_user_subject->delete();
				$_user_subject->insert($_subject);

			DB::commit();

			return redirect()->route( self::URL_INDEX )->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {

			DB::rollBack();
			report( $e );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function destroy(Request $request)
	{
		try {

			$teacher = $this->_userRepo->fetchWhere([ 'id' => $request->id, 'role' => EUser::TEACHER ])->first();

            if(!empty($teacher)){

                //$teacher->status = EUser::STATUS_DELETED;
                // $teacher->save();

                // send mail
                $send_mail_data = [
                    'name' => $teacher->name,
                    'email' => $teacher->email
                ];

                event(new SendMailRemoveAccountEvent($send_mail_data));

				$teacher->delete();
            }

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function storeStatus(Request $request)
	{
		try {

			$_item = $this->_userRepo->fetchWhere([ 'id' => $request->id, 'role' => EUser::TEACHER  ])->update( $request->only('status') );
			return response()->json( $_item ,200);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return response()->json( $e->getMessage() ,400);
			//return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function unlock(Request $request)
	{
		try {
			DB::beginTransaction();

			$_item = $this->_userRepo->fetchWhere([ 'id' => $request->id, 'role' => EUser::TEACHER, 'status' => EUser::STATUS_PENDING, 'approved_admin' => 1, 'percent_training' => 100  ])->first();
			// $_item = VerifyUser::where(['user_id' => $request->id, 'approved_admin' => 1, 'percent_training' => 100])->first();

			if(!$_item)
				throw new Exception( trans("common.error") );

			Storage::delete($_item->card_id);

			// $_item->unlock = 1;
			// $_item->card_id = null;

			$_item->status = EUser::STATUS_ACTIVE;
			$_item->save();


            event( new SendMailActivatedTeacherAccount(['email' => $_item->email,'last_name' => $_item->last_name]) );

			DB::commit();
			return redirect()->back()->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			DB::rollBack();
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );

		}
	}

}
