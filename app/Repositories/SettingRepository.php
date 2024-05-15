<?php

namespace App\Repositories;


use App\Models\Request;
use App\Models\Setting;
use Carbon\Carbon;

class SettingRepository extends BaseRepository
{
	public $model;

	public function __construct(Setting $model)
  {
        parent::__construct($model);
  }


}
