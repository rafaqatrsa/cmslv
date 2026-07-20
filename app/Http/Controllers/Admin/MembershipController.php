<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MembershipController extends Controller
{
    public function index(Request $request): View
    {
        $members = LibraryMember::query()
            ->with('branch')
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = $request->string('search')->toString();

                $query->where('library_card_no', 'like', "%{$search}%")
                    ->orWhere('member_type', 'like', "%{$search}%");
            })
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.membership.index', compact('members'));
    }
}
