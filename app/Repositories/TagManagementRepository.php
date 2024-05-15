<?php

namespace App\Repositories;


use App\Models\Tag;
use Carbon\Carbon;

class TagManagementRepository extends BaseRepository
{
	public $model;

	public function __construct(Tag $model)
  {
        parent::__construct($model);
  }


}
