<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Schema::hasTable((new Staff)->getTable())) {
            return $next($request);
        }

        $hasStaffRecord = Staff::query()
            ->where('user_id', $request->user()?->id)
            ->active()
            ->exists();

        abort_unless($hasStaffRecord, 403);

        return $next($request);
    }
}
