<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $seo['title'] ?? config('app.name', 'Laravel') }}</title>
        <meta name="description" content="{{ $seo['description'] ?? '' }}">
        <meta name="keywords" content="{{ $seo['keywords'] ?? '' }}">
        <link rel="canonical" href="{{ $seo['canonical_url'] ?? url()->current() }}">
        <meta property="og:title" content="{{ $seo['og_title'] ?? ($seo['title'] ?? config('app.name', 'Laravel')) }}">
        <meta property="og:description" content="{{ $seo['og_description'] ?? ($seo['description'] ?? '') }}">
        @if (! empty($seo['og_image']))
            <meta property="og:image" content="{{ asset($seo['og_image']) }}">
        @endif
    </head>
    <body>
        @include('frontend.partials.header')
        @include('frontend.partials.navbar')

        <main>
            @include('frontend.partials.alerts')
            @yield('content')
        </main>

        @include('frontend.partials.footer')
    </body>
</html>
