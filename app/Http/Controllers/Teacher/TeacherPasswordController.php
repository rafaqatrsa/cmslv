<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TeacherPasswordController extends Controller
{
    public function edit(): View
    {
        return view('teacher.password.edit');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! Hash::check($request->string('current_password')->toString(), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => __('The current password is incorrect.'),
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($request->string('password')->toString()),
        ])->save();

        return redirect()
            ->route('teacher.password.edit')
            ->with('status', __('Password updated successfully.'));
    }
}
