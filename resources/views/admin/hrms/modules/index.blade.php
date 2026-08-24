@extends('admin.layouts.app')

@section('title', $module['label'])

@section('content')
    @include('admin.hrms.partials.nav')

    @include('admin.partials.module_table_component')
@endsection
