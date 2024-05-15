<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EPage;
use App\Http\Controllers\Controller;
use App\Repositories\RequestRepository;
use App\Requests\RequestManageRequest;
use Illuminate\Http\Request as Rquest;
use Illuminate\Support\Facades\DB;
use Throwable;

class RequestManagementController extends Controller
{
    private $_userRepo;
	private $_order_by;

    private $_request_repo;
    const PATH_INDEX = "pages.admin.request_management";
    const URL_INDEX = "admin.request_management.list";

    public function __construct(RequestRepository $requestRepository)
    {
        $this->_request_repo = $requestRepository;
        parent::__construct();

    }

    public function list(Rquest $request){
        try {

            // $conditions = [];
            // if($request->isMethod('POST'))
            //     $conditions = $request->only('status');
			// $items = $this->_request_repo->getListRequests($conditions);


            $items = $this->_request_repo->model->search( $request->only('eSign_status') )
                           ->whereHas('student')
                           ->orderBy(  $this->_order_by_default[0], $this->_order_by_default[1]   )
                           ->paginate( $this->_limit );

			return view(self::PATH_INDEX.'.list',compact('items'));

		} catch (Throwable $e) {
            dd( $e->getMessage() );
			report( $e->getMessage() );
			// return redirect()->back()->withErrors( trans("common.error") );
		}
    }

	public function show(Rquest $Rquest)
	{
		try {
			$item = $this->_request_repo->fetch($Rquest->id);

			return view(self::PATH_INDEX.'.detail',compact('item'));
		} catch (Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

    public function update(Rquest $request,$id)
    {
        try {
            $response = $this->_request_repo->update($id,['is_displayed' => (int)$request['is_displayed']]);

            return redirect()->route(self::URL_INDEX)->with(['message' => trans('common.success')]);
        } catch (Throwable $e) {
            report( $e->getMessage() );
            return redirect()->back()->withErrors( trans("common.error") );
        }
    }

    public function destroy(Rquest $Rquest)
	{
		try {
            DB::beginTransaction();

			$item = $this->_request_repo->delete($Rquest->id);

            DB::commit();
			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);
		} catch (Throwable $e) {
			report($e->getMessage());
            DB::rollBack();
			return redirect()->back()->withErrors( trans($e->getMessage()) );
		}
	}
}
