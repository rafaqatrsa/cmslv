<?php

namespace App\Http\Controllers\Admin\Adm;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentRegistrationController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('student-registrations', $request);
    }
}
