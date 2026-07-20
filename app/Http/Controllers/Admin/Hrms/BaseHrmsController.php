<?php

namespace App\Http\Controllers\Admin\Hrms;

use App\Http\Controllers\Controller;
use App\Services\Hrms\HrmsIndexService;
use App\Services\Hrms\HrmsModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseHrmsController extends Controller
{
    public function __construct(
        protected readonly HrmsModuleRegistry $registry,
        protected readonly HrmsIndexService $indexService,
    ) {}

    protected function renderIndex(string $moduleKey, Request $request): View
    {
        $module = $this->registry->get($moduleKey);
        $records = $this->indexService->paginate($module, $request);

        return view('admin.hrms.modules.index', compact('moduleKey', 'module', 'records'));
    }
}
