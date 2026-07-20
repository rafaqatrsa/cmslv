<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends BaseUserController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('books', $request);
    }
}
