<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends BaseAccountController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('stock', $request);
    }
}
