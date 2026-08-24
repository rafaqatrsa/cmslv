<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUsernameRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserUsernameController extends Controller
{
    public function edit(): View
    {
        return view('user.username.edit');
    }

    public function update(UpdateUsernameRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $request->user()->forceFill([
                'username' => $request->validated('username'),
            ])->save();
        });

        return redirect()
            ->route('user.username.edit')
            ->with('status', __('Username updated successfully.'));
    }
}
