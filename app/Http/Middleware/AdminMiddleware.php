<?php

namespace App\Http\Middleware;

use App\Models\Role;
use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $attributes = $user->getAttributes();

        if (array_key_exists('is_active', $attributes) && ! in_array((string) $user->is_active, ['1', 'yes', 'active'], true)) {
            abort(403, 'Your account is not active.');
        }

        if (! $this->hasAdminAccess($user)) {
            abort(403, 'You are not authorized to access the admin panel.');
        }

        return $next($request);
    }

    private function hasAdminAccess(mixed $user): bool
    {
        $attributes = $user->getAttributes();

        if (! array_key_exists('role', $attributes) && ! array_key_exists('role_id', $attributes) && ! array_key_exists('user_id', $attributes)) {
            return true;
        }

        $role = strtolower((string) ($user->role ?? ''));

        if (in_array($role, ['admin', 'superadmin', 'staff'], true)) {
            return true;
        }

        $roleId = $user->role_id ?? null;

        if (! $roleId && isset($user->user_id)) {
            $roleId = Staff::query()->where('user_id', $user->user_id)->value('role_id');
        }

        if (! $roleId) {
            return false;
        }

        return Role::query()
            ->whereKey($roleId)
            ->where(function ($query): void {
                $query->where('is_superadmin', 1)
                    ->orWhere(function ($query): void {
                        $query->where(function ($query): void {
                            $query->where('is_active', 1)
                                ->orWhere('is_active', '1')
                                ->orWhere('is_active', 'yes')
                                ->orWhere('is_active', 'active');
                        })
                            ->whereIn('name', ['Admin', 'Super Admin', 'Staff']);
                    });
            })
            ->exists();
    }
}
