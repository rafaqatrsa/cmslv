<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\Teacher\TeacherIndexService;
use App\Services\Teacher\TeacherModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseTeacherController extends Controller
{
    public function __construct(
        protected readonly TeacherModuleRegistry $registry,
        protected readonly TeacherIndexService $indexService,
    ) {}

    protected function renderIndex(string $moduleKey, Request $request): View
    {
        $module = $this->registry->get($moduleKey);
        $records = $this->indexService->paginate($module, $request);

        return view('teacher.modules.index', compact('moduleKey', 'module', 'records'));
    }
}
