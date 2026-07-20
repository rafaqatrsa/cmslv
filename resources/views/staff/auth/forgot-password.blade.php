<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Staff Forgot Password - {{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    </head>
    <body class="min-h-screen bg-neutral-100 text-neutral-900">
        <main class="flex min-h-screen items-center justify-center px-4 py-10">
            <section class="w-full max-w-md rounded-lg border border-neutral-200 bg-white p-6 shadow-sm">
                <h1 class="text-2xl font-semibold">Staff Forgot Password</h1>

                @if (session('status'))
                    <p class="mt-4 rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-800">{{ session('status') }}</p>
                @endif

                <form method="POST" action="{{ route('staff.forgot_password', absolute: false) }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="staff-forgot-email" class="mb-1 block text-sm font-medium text-neutral-700">Email</label>
                        <input id="staff-forgot-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus class="w-full rounded-md border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-neutral-900 focus:ring-2 focus:ring-neutral-200">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-white hover:bg-neutral-800">Send reset link</button>
                </form>

                <p class="mt-4 text-center text-sm">
                    <a href="{{ route('staff.login', absolute: false) }}" class="font-medium text-blue-700">Back to staff login</a>
                </p>
            </section>
        </main>
    </body>
</html>
