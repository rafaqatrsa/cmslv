<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseReturnController extends BaseAccountController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('purchase-returns', $request);
    }
}
