<?php

namespace App\Http\Controllers\Admin\Adm;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffIdCardGeneratorController extends BaseAdmController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('staff-id-card-generator', $request);
    }
}
