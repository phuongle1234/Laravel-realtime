<?php

namespace App\Repositories;


use App\Models\VerifyUser;


class VerifyUserRepository extends BaseRepository
{
	public $model;

	public function __construct(VerifyUser $model)
  {
        parent::__construct($model);
  }


}
