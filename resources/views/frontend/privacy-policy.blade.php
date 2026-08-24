@extends('frontend.layouts.app')

@section('content')
    <article>
        <h1>{{ $page?->title ?? 'Privacy Policy' }}</h1>

        @if (! empty($page?->description))
            <div>{!! $page->description !!}</div>
        @else
            <p>Privacy policy content will be published here.</p>
        @endif
    </article>
@endsection
