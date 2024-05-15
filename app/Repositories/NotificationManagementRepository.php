<?php

namespace App\Repositories;


use App\Models\ManageNotificationTemplate;
use Carbon\Carbon;

class NotificationManagementRepository extends BaseRepository
{
	public $model;

	public function __construct(ManageNotificationTemplate $model)
  {
        parent::__construct($model);
  }


}
