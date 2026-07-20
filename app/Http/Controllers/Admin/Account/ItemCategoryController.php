<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ItemCategoryController extends BaseAccountController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('item-categories', $request);
    }
}
