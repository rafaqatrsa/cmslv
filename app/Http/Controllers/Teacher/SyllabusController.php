<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SyllabusController extends BaseTeacherController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('syllabus', $request);
    }
}
