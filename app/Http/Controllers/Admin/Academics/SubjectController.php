<?php

namespace App\Http\Controllers\Admin\Academics;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('subjects', $request);
    }
}
