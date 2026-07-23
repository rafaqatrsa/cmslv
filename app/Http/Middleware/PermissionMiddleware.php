<?php

namespace App\Http\Middleware;

use App\Models\PermissionCategory;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission, string $action = 'view'): Response
    {
        $user = $request->user();

        if (! $user || ! $this->canAccess($user, $permission, $action, $request)) {
            abort(403, 'You do not have permission to access this module.');
        }

        return $next($request);
    }

    private function canAccess(mixed $user, string $permission, string $action, Request $request): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }

        if (! Schema::hasTable((new PermissionCategory)->getTable()) || ! Schema::hasTable((new RolePermission)->getTable())) {
            return true;
        }

        $roleId = $this->roleId($user);

        if (! $roleId) {
            return true;
        }

        $categoryId = PermissionCategory::query()
            ->where('short_code', $permission)
            ->orWhere('name', $permission)
            ->value('id');

        if (! $categoryId) {
            return true;
        }

        $column = match ($action) {
            'branch' => 'can_branch',
            'add' => 'can_add',
            'edit' => 'can_edit',
            'delete' => 'can_delete',
            default => 'can_view',
        };

        $branchKeys = config('legacy.session.branch_keys', ['brc_id', 'branch_id']);
        $branchId = null;

        foreach ($branchKeys as $key) {
            $value = $request->session()->get($key);

            if (is_numeric($value)) {
                $branchId = (int) $value;
                break;
            }
        }

        return RolePermission::query()
            ->where('role_id', $roleId)
            ->where('perm_cat_id', $categoryId)
            ->when($branchId !== null, fn ($query) => $query->where(function ($query) use ($branchId): void {
                $query->whereNull('brc_id')->orWhere('brc_id', $branchId);
            }))
            ->where($column, 1)
            ->exists();
    }

    private function isSuperAdmin(mixed $user): bool
    {
        $role = strtolower((string) ($user->role ?? ''));

        if (in_array($role, ['superadmin', 'super admin'], true)) {
            return true;
        }

        $roleId = $this->roleId($user);

        return $roleId && Role::query()->whereKey($roleId)->where('is_superadmin', 1)->exists();
    }

    private function roleId(mixed $user): ?int
    {
        if (isset($user->role_id)) {
            return (int) $user->role_id;
        }

        if (isset($user->role) && ctype_digit((string) $user->role)) {
            return (int) $user->role;
        }

        if (isset($user->role)) {
            return Role::query()->where('name', $user->role)->value('id');
        }

        if (isset($user->user_id)) {
            return Staff::query()->where('user_id', $user->user_id)->value('role_id');
        }

        return null;
    }
}
