<?php

namespace App\Http\Controllers\Admin\Adm;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintSourceController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('complaint-sources', $request);
    }
}
