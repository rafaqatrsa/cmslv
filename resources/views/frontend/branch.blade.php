@extends('frontend.layouts.app')

@section('content')
    <section>
        <h1>{{ $branch->name }}</h1>

        @if (! empty($branch->websiteurl))
            <p>{{ $branch->websiteurl }}</p>
        @endif

        @if (! empty($settings?->address))
            <p>{{ $settings->address }}</p>
        @endif
    </section>

    <section>
        <h2>Pages</h2>

        @forelse ($branch->pages as $page)
            <article>
                <h3>{{ $page->title }}</h3>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $page->description), 120) }}</p>
            </article>
        @empty
            <p>No branch pages are available.</p>
        @endforelse
    </section>

    <section>
        <h2>Updates</h2>

        @forelse ($branch->posts as $post)
            <article>
                <h3>{{ $post->title }}</h3>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $post->description), 120) }}</p>
            </article>
        @empty
            <p>No branch updates are available.</p>
        @endforelse
    </section>
@endsection
