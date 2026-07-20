<?php

namespace App\Http\Controllers\Admin\Adm;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintTypeController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('complaint-types', $request);
    }
}
