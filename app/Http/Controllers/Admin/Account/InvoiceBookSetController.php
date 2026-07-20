<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceBookSetController extends BaseAccountController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('invoice-book-sets', $request);
    }
}
