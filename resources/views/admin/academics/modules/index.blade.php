@extends('admin.layouts.app')

@section('title', $module['label'])

@section('content')
    @include('admin.academics.partials.nav')

    @include('admin.partials.module_table_component')
@endsection
