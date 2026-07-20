@extends('admin.layouts.app')

@section('title', 'Front CMS')

@section('content')
    <div class="mb-4 rounded border border-neutral-200 bg-white p-4">
        <p class="text-sm text-neutral-500">Media files</p>
        <p class="text-2xl font-semibold">{{ number_format($mediaCount) }}</p>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <section class="rounded border border-neutral-200 bg-white">
            <h2 class="border-b border-neutral-200 px-4 py-3 font-semibold">Pages</h2>
            @forelse ($pages as $page)
                <article class="border-b border-neutral-100 px-4 py-3">
                    <h3 class="font-medium">{{ $page->title }}</h3>
                    <p class="text-sm text-neutral-600">{{ $page->slug }}</p>
                </article>
            @empty
                <p class="px-4 py-3 text-sm text-neutral-600">No CMS pages found.</p>
            @endforelse
        </section>

        <section class="rounded border border-neutral-200 bg-white">
            <h2 class="border-b border-neutral-200 px-4 py-3 font-semibold">Posts</h2>
            @forelse ($posts as $post)
                <article class="border-b border-neutral-100 px-4 py-3">
                    <h3 class="font-medium">{{ $post->title }}</h3>
                    <p class="text-sm text-neutral-600">{{ $post->slug }}</p>
                </article>
            @empty
                <p class="px-4 py-3 text-sm text-neutral-600">No CMS posts found.</p>
            @endforelse
        </section>
    </div>
@endsection
