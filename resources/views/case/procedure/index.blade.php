@extends('adminlte::page')
@section('title','Lista de Tramitação')
@section('plugins.Datatables', true)
@section('plugins.Summernote', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.BsCustomFileinput', true)
<x-page-header title="Tramitações">
</x-page-header>
@section('content')
    <x-page-messages/>
    @include('case.procedure.list')
    @include('case.procedure.response.create')
@endsection
