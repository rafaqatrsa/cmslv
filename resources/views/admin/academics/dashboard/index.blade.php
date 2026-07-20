@extends('admin.layouts.app')

@section('title', 'Academics')

@section('content')
    @include('admin.academics.partials.nav', ['modules' => $modules])

    <section class="rounded border border-amber-200 bg-amber-50 p-4 text-amber-900">
        <h2 class="font-semibold">Migration status</h2>
        <p class="mt-2 text-sm">
            These Laravel routes and indexes are mapped to confirmed legacy database tables. The original CodeIgniter Academics controllers, models, views, and JavaScript were not present in this workspace, so deeper actions such as calculations, imports, DataTables JSON, paper generation, and marks processing are documented as unresolved until those files are available.
        </p>
    </section>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($modules as $key => $module)
            <a href="{{ route($module['route']) }}" class="rounded border border-neutral-200 bg-white p-4 hover:border-blue-300">
                <p class="text-sm text-neutral-500">{{ $module['table'] }}</p>
                <h2 class="mt-1 font-semibold">{{ $module['label'] }}</h2>
                <p class="mt-2 text-sm text-neutral-600">Legacy URL: /admin/academics/{{ $module['permission'] }}</p>
            </a>
        @endforeach
    </div>
@endsection
