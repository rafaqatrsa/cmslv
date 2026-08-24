@extends('admin.layouts.app')

@section('title', $module['label'])

@section('content')
    @include('admin.adm.partials.nav')

    @include('admin.partials.module_table_component')
@endsection
