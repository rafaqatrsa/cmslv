@extends('frontend.layouts.app')

@section('content')
    <article>
        <p><a href="{{ route('frontend.branch', $branchRecord) }}">{{ $branchRecord->name }}</a></p>
        <h1>{{ $page->title }}</h1>

        @if (! empty($page->feature_image))
            <img src="{{ asset($page->feature_image) }}" alt="{{ $page->title }}" height="240">
        @endif

        <div>{!! $page->description !!}</div>
    </article>
@endsection
