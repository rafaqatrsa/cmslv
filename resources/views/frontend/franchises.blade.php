@extends('frontend.layouts.app')

@section('content')
    <section>
        <h1>Franchises</h1>

        @forelse ($franchises as $franchise)
            <article>
                <h2><a href="{{ route('frontend.branch', $franchise->id) }}">{{ $franchise->name }}</a></h2>
                @if (! empty($franchise->websiteurl))
                    <p>{{ $franchise->websiteurl }}</p>
                @endif
            </article>
        @empty
            <p>No franchise locations are available.</p>
        @endforelse

        {{ $franchises->links() }}
    </section>
@endsection
