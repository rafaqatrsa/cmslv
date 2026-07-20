<?php

namespace App\Http\Controllers\Admin\Academics;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TestGroupController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('test-groups', $request);
    }
}
