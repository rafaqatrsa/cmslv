<?php

namespace App\Http\Controllers\Admin\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\FrontIndexService;
use App\Services\Front\FrontModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseFrontController extends Controller
{
    public function __construct(
        protected readonly FrontModuleRegistry $registry,
        protected readonly FrontIndexService $indexService,
    ) {}

    protected function renderIndex(string $moduleKey, Request $request): View
    {
        $module = $this->registry->get($moduleKey);
        $records = $this->indexService->paginate($module, $request);

        return view('admin.front.modules.index', compact('moduleKey', 'module', 'records'));
    }
}
