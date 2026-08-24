<?php

namespace App\Http\Middleware;

use App\Services\User\UserContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentOrParent
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse('site');

        $context = app(UserContext::class);

        abort_unless($context->isStudent() || $context->isParent(), 403);

        return $next($request);
    }
}
