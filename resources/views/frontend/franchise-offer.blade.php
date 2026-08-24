@extends('frontend.layouts.app')

@section('content')
    <article>
        <h1>{{ $page?->title ?? 'Franchise Offer' }}</h1>

        @if (! empty($page?->description))
            <div>{!! $page->description !!}</div>
        @else
            <p>Franchise offer details will be published here.</p>
        @endif
    </article>
@endsection
