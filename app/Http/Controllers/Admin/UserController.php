<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
//use App\Traits\VimeoApiTrait;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Enums\EUser;
use App\Requests\AdmitManageRequest;
use Illuminate\Support\Facades\Crypt;
use Hash;


class UserController extends Controller
{
	private $_userRepo;
	const URL_INDEX = "admin.adminManagement.list";
	const PATH_INDEX = "pages.admin.admin_management";

    public function __construct(UserRepository $userRepo)
	{

		$this->_userRepo = $userRepo;
		parent::__construct();
	}

	public function index(Request $request)
	{
		try {

			$item = $this->_userRepo->fetchWhere(['role' => EUser::ADMIN, 'status' => EUser::STATUS_ACTIVE])
			                        ->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
									->paginate( $this->_limit );

			return view(self::PATH_INDEX.'.list', compact('item') );

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}
	}



	public function show(Request $request, $id)
	{

		try {
			$item = $this->_userRepo->fetchWhere(['id' => $id, 'status' => EUser::STATUS_ACTIVE])->first();
			return view(self::PATH_INDEX.'.edit', compact('item') );

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}

	}

	public function update(AdmitManageRequest $request, $id)
	{

		try {
			$item = $this->_userRepo->fetchWhere(['id' => $id, 'status' => EUser::STATUS_ACTIVE])->first();
			$item->name = $request->name;
			$item->email = $request->email;
			$item->password = Hash::make($request->password);
			$item->password_crypt = Crypt::encryptString($request->password);
			$item->save();

			return redirect()->route(self::URL_INDEX)->with(['message'=>trans("common.success")]);

		} catch (\Throwable $e) {
			report( $e->getMessage() );
			return redirect()->back()->withErrors( trans("common.error") );
		}

	}



}
