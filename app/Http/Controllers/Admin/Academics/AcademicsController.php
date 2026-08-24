<?php

namespace App\Http\Controllers\Admin\Academics;

use App\Services\Academics\AcademicModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicsController extends BaseAcademicsController
{
    public function index(Request $request): View
    {
        $modules = $this->registry->all();

        return view('admin.academics.dashboard.index', compact('modules'));
    }
}
