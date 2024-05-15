<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\EStatus;
use App\Enums\ETransfer;
use App\Enums\EUser;
use App\Http\Requests\Teacher\HistoryRewardRequest;
use App\Http\Requests\Teacher\RewardRequest;
use App\Repositories\UserCompensationRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Throwable;

class RewardController extends Controller
{
    private $userCompensationRepo;
    private $userRepo;

    const PATH_INDEX = "pages.teacher.reward";

    public function __construct
    (
        UserCompensationRepository $userCompensationRepository,
        UserRepository $userRepository
    ) {
        $this->userRepo = $userRepository;
        $this->userCompensationRepo = $userCompensationRepository;
    }

    public function index(RewardRequest $request)
    {
        if ($request->isMethod("POST")) {
            try {

                DB::beginTransaction();
                $teacher_id = auth()->guard('teacher')->id();
                $request_amount = (int)$request['amount'];

                $teacher = $this->userRepo->fetchWhere(['id' => $teacher_id, 'role' => EUser::TEACHER])
                    ->with('transfer')
                    ->whereNotIn('status', [EUser::STATUS_DELETED])
                    ->first();

                // if request amount larger than current reward, cannot make request
                if ($request_amount > $teacher->reward)
                    return redirect()->back()->withErrors(trans("common.reward_error"));

                $total_current_reward = $teacher->reward - $request_amount;

                if ($total_current_reward < 0)
                    return redirect()->back()->withErrors(trans("common.reward_error"));

                $insert_data = [
                    'user_id' => $teacher->id,
                    'amount' => $request_amount,
                    'status' => EStatus::PENDING,
                    'note' => 'moi vua yeu cau, chua duoc xu ly'
                ];

                $success = $this->userCompensationRepo->store($insert_data);

                // update reward
                $teacher->transfer()->create([
                    'user_id' => $teacher->id,
                    'amount' => -$request_amount,
                    'current_reward' => $total_current_reward,
                    'status' => EStatus::ACTIVE,
                    'note' => 'tru tien khi rut tien',
                    'action' => ETransfer::REWARD_REQUEST
                ]);

                DB::commit();
                return redirect()->back()->with(['message' => trans('common.success')]);
            } catch (Throwable $e) {
                report($e->getMessage());
                DB::rollBack();
                return redirect()->back()->withErrors(trans('common.error'));
            }
        }

        $teacher = auth()->guard('teacher')->user();

        if (empty($teacher)) {
            return abort(404);
        }

        return view(self::PATH_INDEX . '.index', compact('teacher'));
    }

    public function history(HistoryRewardRequest $request)
    {
        $conditions = [];
        $teacher = auth()->guard('teacher')->user();

        if($request->isMethod('POST')){
            $exploded_data = explode('-',$request['from_date']);
            $conditions['year'] = $exploded_data[0];
            $conditions['month'] = $exploded_data[1];
        }


        $list_statistical = $teacher->statistical()->where($conditions)->get();

        return view(self::PATH_INDEX . '.history',compact('teacher','list_statistical'));
    }
}
