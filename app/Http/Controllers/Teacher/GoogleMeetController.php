<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GoogleMeetController extends BaseTeacherController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('google-meet', $request);
    }
}
