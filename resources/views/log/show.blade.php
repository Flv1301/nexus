{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/03/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Logs de atividades')
@section('plugins.Datatables', true)
@section('plugins.TempusDominusBs4', true)
@php
    $config = ['format' => 'DD/MM/YYYY'];
@endphp
<x-page-header title="Log de Atividades de Usuário">
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <p><span class="text-black">Data: </span>{{$log->created_at}}</p>
            <p><span class="text-black">Evento:</span> {{ $log->description ?? ''}}</p>
            <p><span class="text-black">Registrado por:</span> {{ $log->causer->name ?? ''}}</p>
            <span class="text-lg text-info">Modelo</span>
            <p>{{$log->subject ?? ''}}</p>
            <span class="text-lg text-info">Log</span>
            <p>{{$log->changes ?? ''}}</p>
        </div>
    </div>
@endsection
