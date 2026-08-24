<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TestScheduleController extends BaseTeacherController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('test-schedules', $request);
    }
}
