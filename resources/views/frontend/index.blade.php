@extends('frontend.layouts.app')

@section('content')
    <section>
        <h1>{{ $homePage?->title ?? ($settings?->name ?? config('app.name', 'Laravel')) }}</h1>

        @if (! empty($homePage?->description))
            <div>{!! $homePage->description !!}</div>
        @else
            <p>Welcome to {{ $settings?->name ?? config('app.name', 'Laravel') }}.</p>
        @endif
    </section>

    <section>
        <h2>Branches</h2>

        @forelse ($branches as $branch)
            <article>
                <h3><a href="{{ route('frontend.branch', $branch) }}">{{ $branch->name }}</a></h3>
                @if (! empty($branch->websiteurl))
                    <p>{{ $branch->websiteurl }}</p>
                @endif
            </article>
        @empty
            <p>No active branches are available.</p>
        @endforelse
    </section>

    <section>
        <h2>Latest Updates</h2>

        @forelse ($posts as $post)
            <article>
                <h3>{{ $post->title }}</h3>
                @if (! empty($post->publish_date))
                    <time datetime="{{ $post->publish_date->toDateString() }}">{{ $post->publish_date->toFormattedDateString() }}</time>
                @endif
                <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $post->description), 160) }}</p>
            </article>
        @empty
            <p>No published updates are available.</p>
        @endforelse
    </section>

    <section>
        <h2>Gallery</h2>

        @forelse ($gallery as $item)
            @if (! empty($item->thumb_path) || ! empty($item->image))
                <img src="{{ asset($item->thumb_path ?: $item->image) }}" alt="{{ $item->img_name ?: 'Gallery image' }}" height="120">
            @endif
        @empty
            <p>No gallery items are available.</p>
        @endforelse
    </section>
@endsection
