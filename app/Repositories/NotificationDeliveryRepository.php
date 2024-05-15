<?php

namespace App\Repositories;


use App\Models\ManageNotificationTemplate;
use App\Models\NotificationDelivery;
use Carbon\Carbon;

class NotificationDeliveryRepository extends BaseRepository
{
	public $model;

	public function __construct(NotificationDelivery $model)
  {
        parent::__construct($model);
  }


}
