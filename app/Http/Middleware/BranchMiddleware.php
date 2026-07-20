<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class BranchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $branchId = $request->session()->get('brc_id', $request->session()->get('branch_id'));

        if ($branchId && Schema::hasTable((new Branch)->getTable()) && ! Branch::query()->whereKey($branchId)->active()->exists()) {
            $request->session()->forget(['brc_id', 'branch_id']);

            return redirect()->route('admin.dashboard')->with('error', 'The selected branch is no longer available.');
        }

        return $next($request);
    }
}
