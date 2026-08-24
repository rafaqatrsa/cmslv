<?php

namespace App\Http\Controllers\Admin\Account;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassBookSetController extends BaseAccountController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('class-book-sets', $request);
    }
}
