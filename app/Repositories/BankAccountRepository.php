<?php

namespace App\Repositories;


use App\Models\BankAccount;
use Carbon\Carbon;

class BankAccountRepository extends BaseRepository
{
	public $model;

	public function __construct(BankAccount $model)
  {
        parent::__construct($model);
  }


}
