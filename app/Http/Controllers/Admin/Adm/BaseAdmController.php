<?php

namespace App\Http\Controllers\Admin\Adm;

use App\Http\Controllers\Controller;
use App\Services\Adm\AdmIndexService;
use App\Services\Adm\AdmModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseAdmController extends Controller
{
    public function __construct(
        protected readonly AdmModuleRegistry $registry,
        protected readonly AdmIndexService $indexService,
    ) {}

    protected function renderIndex(string $moduleKey, Request $request): View
    {
        $module = $this->registry->get($moduleKey);
        $records = $this->indexService->paginate($module, $request);

        return view('admin.adm.modules.index', compact('moduleKey', 'module', 'records'));
    }
}
