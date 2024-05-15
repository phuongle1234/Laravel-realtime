<?php

namespace App\Services;

use App\Repositories\VimeoRepository;
use App\Traits\VimeoApiTrait;

class VimeoService
{
    use VimeoApiTrait;

    private $vimeoRepo;

    public $_client_id;
    public $_client_secret;
    public $_access_token;
    // public $_id_vimeo;

    public function __construct(VimeoRepository $vimeoRepository)
    {
        //$this->vimeoRepo = $vimeoRepository;

        //$this->retrieve();
        //$this->setEnvironments();
    }

    public function retrieve()
    {

        $_item = $this->vimeoRepo->retrieve()[0];

        $this->_client_id = $_item->client_id;
        $this->_access_token = $_item->personal_access_token;
        $this->_client_secret = $_item->client_secrets;
        // $this->_id_vimeo = $_item->id;
    }


}
