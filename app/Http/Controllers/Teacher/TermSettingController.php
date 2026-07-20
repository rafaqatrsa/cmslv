<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TermSettingController extends BaseTeacherController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('term-settings', $request);
    }
}
