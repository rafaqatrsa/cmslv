<?php

namespace App\Http\Controllers\Admin\Hrms;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HrManualController extends BaseHrmsController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('manual', $request);
    }
}
