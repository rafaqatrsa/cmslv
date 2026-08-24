<header>
    <a href="{{ route('frontend.home') }}">
        @if (! empty($frontSettings?->logo))
            <img src="{{ asset($frontSettings->logo) }}" alt="{{ $settings?->name ?? config('app.name', 'Laravel') }}" height="48">
        @else
            {{ $settings?->name ?? config('app.name', 'Laravel') }}
        @endif
    </a>

    @if (! empty($settings?->phone) || ! empty($settings?->email))
        <p>
            @if (! empty($settings?->phone))
                <span>{{ $settings->phone }}</span>
            @endif

            @if (! empty($settings?->email))
                <span>{{ $settings->email }}</span>
            @endif
        </p>
    @endif
</header>
