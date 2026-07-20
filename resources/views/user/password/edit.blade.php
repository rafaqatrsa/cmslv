@extends('user.layouts.app')

@section('title', 'Change Password')

@section('content')
    @include('user.partials.nav')

    <form method="POST" action="{{ route('user.password.update') }}" class="max-w-xl rounded border border-neutral-200 bg-white p-4">
        @csrf

        <div class="mb-4">
            <label for="current_password" class="mb-1 block text-sm font-medium">Current password</label>
            <input id="current_password" name="current_password" type="password" class="w-full rounded border border-neutral-300 px-3 py-2" autocomplete="current-password">
            @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="mb-1 block text-sm font-medium">New password</label>
            <input id="password" name="password" type="password" class="w-full rounded border border-neutral-300 px-3 py-2" autocomplete="new-password">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="mb-1 block text-sm font-medium">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded border border-neutral-300 px-3 py-2" autocomplete="new-password">
        </div>

        <button class="rounded bg-blue-600 px-4 py-2 text-white">Update password</button>
    </form>
@endsection
