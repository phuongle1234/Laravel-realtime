<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Repositories\NotificationManagementRepository;
use App\Requests\NotificationManageRequest;

use Illuminate\Support\Facades\DB;

class NotificationManagementController extends Controller
{
	const URL_INDEX = "admin.notificationManagement.list";
	const PATH_INDEX = "pages.admin.notification_management";
	private $_order_by;

    public function __construct(NotificationManagementRepository $notiManageRepo)
	{
		$this->_notiManageRepo = $notiManageRepo;
		parent::__construct();
	}

    public function index(Request $request)
	{
		try {
			$item = $this->_notiManageRepo->model
					->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
					->paginate( $this->_limit );

			return view(self::PATH_INDEX.'.list',compact('item'));

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

    public function show(Request $request, $id)
	{
		try {
			$item = $this->_notiManageRepo->fetchWhere([ 'id' => $id ])->first();
			return view(self::PATH_INDEX.'.edit',compact('item'));
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function update(NotificationManageRequest $request, $id)
	{
		try {

			$this->_notiManageRepo->fetchWhere([ 'id' => $id ])->update(
						$request->only(
							'name',
							'title',
							'content',
							'destination',
							'display'
						)
					);

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function create(Request $request)
	{
		try {
			return view(self::PATH_INDEX.'.add');
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function store(NotificationManageRequest $request)
	{
		try {

			$this->_notiManageRepo->store(
				$request->only(
					'name',
					'title',
					'content',
					'destination',
					'display'
				)
			);

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function destroy(Request $request)
	{
		try {
			$item = $this->_notiManageRepo->softDeleted( $request->id );
			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

    public function updateNotification(Request $request)
    {
        try {

            $notification = DB::table('notifications')->where('id', $request->id )->update(['read_at' => Carbon::now()]);
            $count = auth()->guard('admin')->user()->unreadnotifications()->count();

            return response()->json(['message' => trans('common.success'),'count' => $count]);
        } catch (\Throwable $e) {
            report( $e->getMessage() );
            return response()->json(['error' => trans('common.error')],400);
        }
    }
}
