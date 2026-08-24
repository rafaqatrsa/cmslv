<?php

namespace App\Http\Controllers\Admin\Academics;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GroomingController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        return $this->renderIndex('grooming', $request);
    }
}
