<?php

namespace App\Http\Controllers\Admin\Academics;

use Illuminate\Http\Request;
use Illuminate\View\View;

class WeekSettingController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('week-settings', $request);
    }
}
