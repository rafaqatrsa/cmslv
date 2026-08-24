@extends('user.layouts.app')

@section('title', 'Change Username')

@section('content')
    @include('user.partials.nav')

    <form method="POST" action="{{ route('user.username.update') }}" class="max-w-xl rounded border border-neutral-200 bg-white p-4">
        @csrf

        <div class="mb-4">
            <label for="username" class="mb-1 block text-sm font-medium">Username</label>
            <input id="username" name="username" value="{{ old('username', auth()->user()->username ?? '') }}" class="w-full rounded border border-neutral-300 px-3 py-2" autocomplete="username">
            @error('username')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button class="rounded bg-blue-600 px-4 py-2 text-white">Update username</button>
    </form>
@endsection
