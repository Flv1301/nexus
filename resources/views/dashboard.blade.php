@extends('adminlte::page')
@push('css')
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush
@section('title', 'Dashboard')
@section('content')
    <div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
        <img src="{{ asset('images/logo_mppa_transparente.png') }}" alt="Logo" style="max-width: 300px;">
    </div>
@endsection

