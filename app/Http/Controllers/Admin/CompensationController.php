<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CompensationRequest;
use App\Repositories\UserCompensationRepository;
use App\Services\TransferService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CompensationController extends Controller
{
    private $compensationRepo;
    private $transferService;

    const PATH_INDEX = 'pages.admin.compensation_management.';
    const URL_INDEX = 'admin.compensation_management.';

    public function __construct(
        UserCompensationRepository $userCompensationRepository,
        TransferService $transferService
    )
    {
        $this->transferService = $transferService;
        $this->compensationRepo = $userCompensationRepository;
    }

    public function list()
    {
        $compensations = $this->compensationRepo->getAllCompensations();
        return view(self::PATH_INDEX.'list',compact('compensations'));
    }

    public function history(CompensationRequest $request)
    {
        $conditions = [];
        if($request->isMethod('POST')){
            $conditions = $request->only('from_date','to_date');
        }

        $histories = $this->compensationRepo->getAllCompensationLogs($conditions);
        return view(self::PATH_INDEX.'history',compact('histories'));
    }

    public function export()
    {
        try{

            DB::beginTransaction();

            $response = $this->transferService->generateCSV();

            if(!$response)
                return redirect()->back()->withErrors(trans('common.error'));

            DB::commit();

            return response()->download(public_path('storage/transfer/'.$response));
//            return redirect()->back()->with(['message' => trans('common.success')]);

        } catch (Throwable $e){
            report($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(trans('common.error'));
        }

    }

    public function show($id)
    {
        $exported_compensations = $this->compensationRepo->getCompensationsByLogId($id);
        return view(self::PATH_INDEX.'detail',compact('exported_compensations'));
    }
}
