<?php

namespace App\Repositories;

use App\Models\Vimeos;
use Vimeo\Vimeo;

class VimeoRepository extends BaseRepository
{
    public $model;

    public function __construct(Vimeos $model)
    {
        parent::__construct($model);
    }

    public function getVimeoAccount()
    {
        $vimeo = $this->model->first();
        $current_account = new Vimeo( $vimeo->client_id,$vimeo->client_secrets,$vimeo->personal_access_token );
        return $current_account->request('/me');
    }

    public function retrieve()
    {
        return $this->model->all();
    }

}
