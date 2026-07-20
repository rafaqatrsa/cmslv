<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleReturnController extends BaseAccountController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('sales-returns', $request);
    }
}
