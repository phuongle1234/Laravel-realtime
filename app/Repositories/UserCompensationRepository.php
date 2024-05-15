<?php

namespace App\Repositories;

use App\Enums\EPage;
use App\Models\UserCompensationsLogs;
use App\Repositories\BaseRepository;
use App\Enums\EStatus;
use App\Models\UserCompensations;

class UserCompensationRepository extends BaseRepository
{
	public $model;
    public $log;

	public function __construct(UserCompensations $model, UserCompensationsLogs $log)
  {
        parent::__construct($model);
        $this->log = $log;
  }

    public function getAllCompensations()
    {
        return $this->model->where(['status' => EStatus::PENDING])
            ->with(['user.bankAccount'])
            ->latest()
            ->paginate(EPage::E_PER_PAGE_DEFAULT);
    }

    public function getCompensationsByLogId($log_id)
    {
        return $this->model->where(['status' => EStatus::APPROVED,'log_id' => $log_id])
            ->with(['user.bankAccount','log'])
            ->latest()
            ->get();
    }

    public function getCompensationsByUserId($user_id)
    {
        return $this->model->where(['status' => EStatus::APPROVED,'user_id' => $user_id])
            ->latest()
            ->get();
    }

    public function getAllCompensationLogs($conditions = [])
    {
        $result = $this->log->where(['status' => EStatus::APPROVED]);

        if(!empty($conditions))
            $result = $result->search($conditions);

        return $result->latest()->paginate(EPage::E_PER_PAGE_DEFAULT);
    }

    public function getCompensationLogsById($id)
    {
        return $this->log->where(['status' => EStatus::APPROVED,'id' => $id])->first();
    }

    public function updateStatusByIDs($ids = [], $conditions = [])
    {
        return $this->model->whereIn('id',$ids)->update($conditions);
    }

    public function isShowNotification()
    {
        return $this->model->where(['status' => EStatus::PENDING])->get()->isNotEmpty();
    }

}
