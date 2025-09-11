@extends('adminlte::page')
@section('title','Lista de Tramitação')
@push('css')
<style>
/* CSS customizado para barra lateral azul - GLOBAL */
.sidebar-dark-primary {
    background-color: #1e3a8a !important;
    background-image: linear-gradient(180deg, #1e3a8a 10%, #1e40af 100%) !important;
}

.sidebar-dark-primary .brand-link {
    /*background-color: rgba(255, 255, 255, 0.1) !important;*/
    background-color: #1e3a8a !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.sidebar-dark-primary .brand-link:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
}
</style>
@endpush
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
