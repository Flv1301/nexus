@extends('adminlte::page')
@section('title', 'GI2')
@section('plugins.Datatables', true)
@include('gi2.header')
@section('content')
    @isset($gi2s)
        @if($gi2s->count())
            <div class="card">
                <div class="card-body">
                    @include('gi2.content')
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <span class="text-lg text-info"> <i class="fas fa-info-circle fa-lg"></i> Sem registro na base de dados para o dado pesquisado!</span>
                </div>
            </div>
        @endif
    @endisset
@endsection

