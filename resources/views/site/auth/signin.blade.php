<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Site Sign In - {{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    </head>
    <body class="min-h-screen bg-neutral-100 text-neutral-900">
        <main class="flex min-h-screen items-center justify-center px-4 py-10">
            <section class="w-full max-w-md rounded-lg border border-neutral-200 bg-white p-6 shadow-sm">
                <div class="mb-6 text-center">
                    <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name', 'Laravel') }}" class="mx-auto mb-4 h-14 w-14">
                    <h1 class="text-2xl font-semibold">Site Sign In</h1>
                </div>

                <form method="POST" action="{{ route($signinRouteName, absolute: false) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="site-email" class="mb-1 block text-sm font-medium text-neutral-700">Student or parent username</label>
                        <input id="site-email" name="email" type="text" value="{{ old('email') }}" autocomplete="username" required autofocus class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-neutral-900 focus:ring-2 focus:ring-neutral-200">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="site-password" class="mb-1 block text-sm font-medium text-neutral-700">Password</label>
                        <input id="site-password" name="password" type="password" autocomplete="current-password" required class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-neutral-900 focus:ring-2 focus:ring-neutral-200">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label for="site-remember" class="flex items-center gap-2 text-sm text-neutral-600">
                        <input id="site-remember" name="remember" type="checkbox" value="1" @checked(old('remember')) class="h-4 w-4 rounded border-neutral-300">
                        Remember me
                    </label>

                    <button type="submit" class="w-full rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">Sign in</button>
                </form>
            </section>
        </main>
    </body>
</html>
