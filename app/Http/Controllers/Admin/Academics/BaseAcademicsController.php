<?php

namespace App\Http\Controllers\Admin\Academics;

use App\Http\Controllers\Controller;
use App\Services\Academics\AcademicIndexService;
use App\Services\Academics\AcademicModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseAcademicsController extends Controller
{
    public function __construct(
        protected readonly AcademicModuleRegistry $registry,
        protected readonly AcademicIndexService $indexService,
    ) {}

    protected function renderIndex(string $moduleKey, Request $request): View
    {
        $module = $this->registry->get($moduleKey);
        $records = $this->indexService->paginate($module, $request);

        return view('admin.academics.modules.index', compact('moduleKey', 'module', 'records'));
    }
}
