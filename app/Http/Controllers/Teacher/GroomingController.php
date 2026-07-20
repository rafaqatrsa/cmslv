<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GroomingController extends BaseTeacherController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('grooming', $request);
    }
}
