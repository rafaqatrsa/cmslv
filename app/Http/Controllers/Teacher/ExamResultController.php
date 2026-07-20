<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamResultController extends BaseTeacherController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('exam-results', $request);
    }
}
