<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Staff Login - {{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    </head>
    <body class="min-h-screen bg-neutral-100 text-neutral-900">
        <main class="flex min-h-screen items-center justify-center px-4 py-10">
            <section class="w-full max-w-md rounded-lg border border-neutral-200 bg-white p-6 shadow-sm">
                <div class="mb-6 text-center">
                    <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name', 'Laravel') }}" class="mx-auto mb-4 h-14 w-14">
                    <h1 class="text-2xl font-semibold">Staff Login</h1>
                    <p class="mt-1 text-sm text-neutral-500">Use your admin or staff account credentials.</p>
                </div>

                <form method="POST" action="{{ route($loginRouteName, absolute: false) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="staff-email" class="mb-1 block text-sm font-medium text-neutral-700">Staff email or employee ID</label>
                        <input id="staff-email" name="email" type="text" value="{{ old('email') }}" autocomplete="username" required autofocus class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-neutral-900 focus:ring-2 focus:ring-neutral-200">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="staff-password" class="mb-1 block text-sm font-medium text-neutral-700">Password</label>
                        <input id="staff-password" name="password" type="password" autocomplete="current-password" required class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-neutral-900 focus:ring-2 focus:ring-neutral-200">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <label for="staff-remember" class="flex items-center gap-2 text-sm text-neutral-600">
                            <input id="staff-remember" name="remember" type="checkbox" value="1" @checked(old('remember')) class="h-4 w-4 rounded border-neutral-300">
                            Remember me
                        </label>

                        <a href="{{ route('staff.forgot_password', absolute: false) }}" class="text-sm font-medium text-blue-700">Forgot password?</a>
                    </div>

                    <button type="submit" class="w-full rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">Log in</button>
                </form>
            </section>
        </main>
    </body>
</html>
