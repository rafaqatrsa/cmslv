<?php

namespace App\Http\Controllers\Admin\Adm;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('leave-requests', $request);
    }
}
