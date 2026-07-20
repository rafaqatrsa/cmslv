<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Student Portal')</title>
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    </head>
    <body class="bg-neutral-100 text-neutral-900">
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <header class="mb-6 flex flex-col gap-3 border-b border-neutral-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">CodeIgniter-compatible student and parent portal</p>
                    <h1 class="text-2xl font-semibold">@yield('title', 'Student Portal')</h1>
                </div>

                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-700">Main dashboard</a>
            </header>

            @yield('content')
        </main>
    </body>
</html>
