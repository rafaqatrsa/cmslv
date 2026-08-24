<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SystemNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = SystemNotification::query()
            ->with(['branch', 'role'])
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = $request->string('search')->toString();

                $query->where(function (Builder $query) use ($search): void {
                    $query->where('notification_title', 'like', "%{$search}%")
                        ->orWhere('notification_desc', 'like', "%{$search}%")
                        ->orWhere('notification_type', 'like', "%{$search}%")
                        ->orWhere('notification_for', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('brc_id'), fn (Builder $query) => $query->where('brc_id', $request->integer('brc_id')))
            ->when($request->filled('type'), fn (Builder $query) => $query->where('notification_type', $request->string('type')->toString()))
            ->when($request->filled('for'), fn (Builder $query) => $query->where('notification_for', $request->string('for')->toString()))
            ->when($request->filled('status'), fn (Builder $query) => $query->where('is_active', $request->string('status')->toString()))
            ->latest('date')
            ->paginate(20)
            ->withQueryString();

        $branches = Branch::query()->active()->orderBy('name')->get();

        return view('admin.system-notifications.index', compact('notifications', 'branches'));
    }
}
