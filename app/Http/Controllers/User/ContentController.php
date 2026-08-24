<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends BaseUserController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('content', $request);
    }
}
