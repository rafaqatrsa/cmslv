<?php

namespace App\Http\Controllers\Admin\Front;

use Illuminate\Http\Request;
use Illuminate\View\View;

class NoticeController extends BaseFrontController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('notices', $request);
    }
}
