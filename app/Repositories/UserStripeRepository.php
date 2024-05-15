<?php

namespace App\Repositories;


use App\Models\UserStripe;

class UserStripeRepository extends BaseRepository
{
	public $model;

	public function __construct(UserStripe $model)
  {
        parent::__construct($model);
  }


}
