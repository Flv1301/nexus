@extends('adminlte::page')

@section('title', 'Dashboard')

@push('css')
<style>
/* CSS customizado para barra lateral azul - GLOBAL */
.sidebar-dark-primary {
    background-color: #1e3a8a !important;
    background-image: linear-gradient(180deg, #1e3a8a 10%, #1e40af 100%) !important;
}

.sidebar-dark-primary .nav-sidebar .nav-link {
    color: rgba(255, 255, 255, 0.9) !important;
}

.sidebar-dark-primary .nav-sidebar .nav-link:hover {
    color: #fff !important;
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.sidebar-dark-primary .nav-sidebar .nav-link.active {
    color: #fff !important;
    background-color: rgba(255, 255, 255, 0.2) !important;
}

.sidebar-dark-primary .brand-link {
    background-color: #1e3a8a !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
}

.sidebar-dark-primary .brand-link:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
}
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
        <img src="{{ asset('images/logo_mppa_transparente.png') }}" alt="Logo" style="max-width: 300px;">
    </div>
@endsection

