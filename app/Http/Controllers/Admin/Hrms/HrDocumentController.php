<?php

namespace App\Http\Controllers\Admin\Hrms;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HrDocumentController extends BaseHrmsController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('documents', $request);
    }
}
