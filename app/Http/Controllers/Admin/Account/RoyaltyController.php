<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RoyaltyController extends BaseAccountController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('royalty', $request);
    }
}
