<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeworkController extends BaseTeacherController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('homework', $request);
    }
}
