<?php

namespace App\Http\Controllers\Admin;

use App\Enums\EStatus;
use App\Enums\ETransfer;
use App\Http\Requests\Admin\LecturerCP;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Enums\EUser;

use Exception;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Throwable;

class LecturerCPController extends Controller
{
    private $_userRepo;
    private $_order_by;
    const URL_INDEX = "admin.lecturerCPManagement.list";
    const PATH_INDEX = "pages.admin.lecturer_cp_management";

    public function __construct(UserRepository $userRepo)
    {
        $this->_userRepo = $userRepo;
        $this->_userRepo->model->prefix = 'admin-lecturer-cp';
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {

            $lecturers = $this->_userRepo->fetchWhere(['role' => EUser::TEACHER, 'status' => EUser::STATUS_ACTIVE ])
                // ->whereNotIn('status', [EUser::STATUS_DELETED])
                // ->whereHas('verifyUser', function ($query) {
                //     $query->where('unlock', '=', "1");
                // })
                ->search($request->only('code', 'name'))
                ->orderBy( $this->_order_by_default[0], $this->_order_by_default[1] )
				->paginate( $this->_limit );

            return view(self::PATH_INDEX . '.list', compact('lecturers'));

        } catch (Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $lecturer = $this->_userRepo->fetchWhere(['id' => $id, 'role' => EUser::TEACHER])
                ->with('transfer')
                ->whereNotIn('status', [EUser::STATUS_DELETED])
                ->first();

            return view(self::PATH_INDEX . '.edit', compact('lecturer'));
        } catch (\Throwable $e) {
            report($e->getMessage());
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }

    public function update(LecturerCP $request, $id)
    {
        try {
            DB::beginTransaction();

            $lecturer = $this->_userRepo->fetchWhere(['id' => $id, 'role' => EUser::TEACHER])
                ->withCount('transfer')
                ->with('transfer')
                ->whereNotIn('status', [EUser::STATUS_DELETED])
                ->first();

            // if lecturer has no transfer, cannot minus
            if ($lecturer->transfer_count === 0 && $request['reward_action'] === ETransfer::MINUS) {
                return redirect()->back()->withErrors(trans("common.error"));
            }

            switch ($request['reward_action']) {
                case ETransfer::MINUS:

                    $total_current_reward = $lecturer->reward - $request['amount'];

                    if ($total_current_reward < 0) {
                        return redirect()->back()->withErrors(trans("common.error"));
                    }

                    // update reward
                    $lecturer->transfer()->create([
                        'user_id' => $lecturer->id,
                        'amount' => -$request['amount'],
                        'current_reward' => $total_current_reward,
                        'status' => EStatus::ACTIVE,
                        'note' => 'tru tien',
                        'action' => ETransfer::MINUS
                    ]);

                    break;

                case ETransfer::BONUS:

                    $total_current_reward = $lecturer->reward + $request['amount'];

                    // update reward
                    $lecturer->transfer()->create([
                        'user_id' => $lecturer->id,
                        'amount' => $request['amount'],
                        'current_reward' => $total_current_reward,
                        'status' => EStatus::ACTIVE,
                        'note' => 'cong tien',
                        'action' => ETransfer::BONUS
                    ]);

                    break;
            }
            DB::commit();
            return redirect()->route(self::URL_INDEX)->with(['message' => trans("common.success")]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->withErrors(trans("common.error"));
        }
    }
}
