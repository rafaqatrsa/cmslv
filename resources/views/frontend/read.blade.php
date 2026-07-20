@extends('frontend.layouts.app')

@section('content')
    <article>
        <p><a href="{{ route('frontend.branch', $branchRecord) }}">{{ $branchRecord->name }}</a></p>
        <h1>{{ $post->title }}</h1>

        @if (! empty($post->publish_date))
            <time datetime="{{ $post->publish_date->toDateString() }}">{{ $post->publish_date->toFormattedDateString() }}</time>
        @endif

        @if (! empty($post->feature_image))
            <img src="{{ asset($post->feature_image) }}" alt="{{ $post->title }}" height="240">
        @endif

        <div>{!! $post->description !!}</div>
    </article>

    <section>
        <h2>Related Updates</h2>

        @forelse ($relatedPosts as $relatedPost)
            <article>
                <h3>{{ $relatedPost->title }}</h3>
                <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) $relatedPost->description), 120) }}</p>
            </article>
        @empty
            <p>No related updates are available.</p>
        @endforelse
    </section>
@endsection
