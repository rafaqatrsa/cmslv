<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserIndexService;
use App\Services\User\UserModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseUserController extends Controller
{
    public function __construct(
        protected readonly UserModuleRegistry $registry,
        protected readonly UserIndexService $indexService,
    ) {}

    protected function renderIndex(string $moduleKey, Request $request): View
    {
        $module = $this->registry->get($moduleKey);
        $records = $this->indexService->paginate($module, $request);

        return view('user.modules.index', compact('moduleKey', 'module', 'records'));
    }
}
