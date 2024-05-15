<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Enums\EPage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected $_order_by_default;
    protected $_limit;

    public function __construct()
    {
        $this->_order_by_default = EPage::getOrderBy();
        $this->_limit = EPage::E_PER_PAGE_DEFAULT;

    }

}
