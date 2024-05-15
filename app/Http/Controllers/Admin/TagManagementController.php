<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TagManagementRepository;
use App\Requests\TagManageRequest;
use App\Enums\EUser;

use App\Models\User;

class TagManagementController extends Controller
{
	const URL_INDEX = "admin.tagManagement.list";
	const PATH_INDEX = "pages.admin.tag_management";
	private $_repo;


    public function __construct(TagManagementRepository $repo)
	{
		$this->_repo = $repo;
		$this->_repo->model->prefix = 'admin-tag';
		parent::__construct();
	}

    public function index(Request $request)
	{
		try {

			$item = $this->_repo->model->with('subject')
					->search($request->only('name','eSign_tag_type','eSign_active','eSign_subject_id'))
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
			$_tag_type = explode('/',request()->route()->getPrefix())[2];
			$item = $this->_repo->fetchWhere([ 'id' => $id ,'tag_type' => $_tag_type])->first();
			return view(self::PATH_INDEX.".{$item->tag_type}.edit",compact('item'));
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function update(TagManageRequest $request, $id)
	{
		try {
			$_tag_type = explode('/',request()->route()->getPrefix())[2];
			$this->_repo->fetchWhere([ 'id' => $id, 'tag_type' => $_tag_type ])->update(
				$request->only('name', 'active', 'subject_id')
			);

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function store(TagManageRequest $request)
	{
		try {

			$_tag_type = explode('/',request()->route()->getPrefix())[2];

			$this->_repo->store( array_merge( $request->only( 'name', 'subject_id', 'status' ),['tag_type' => $_tag_type] ) );

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}

	public function storeStatus(Request $request)
	{
		try {

			$_result = $this->_repo->fetchWhere([ 'id' => $request->id ])->update( $request->only('active') );

			return response()->json($_result,200);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return response()->json($e->getMessage(),400);
		}
	}

	public function destroy(Request $request)
	{
		try {
			$item = $this->_repo->softDeleted( $request->id );
			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);
		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}
}
