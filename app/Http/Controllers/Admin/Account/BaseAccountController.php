<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Services\Account\AccountIndexService;
use App\Services\Account\AccountModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseAccountController extends Controller
{
    public function __construct(
        protected readonly AccountModuleRegistry $registry,
        protected readonly AccountIndexService $indexService,
    ) {}

    protected function renderIndex(string $moduleKey, Request $request): View
    {
        $module = $this->registry->get($moduleKey);
        $records = $this->indexService->paginate($module, $request);

        return view('admin.account.modules.index', compact('moduleKey', 'module', 'records'));
    }
}
