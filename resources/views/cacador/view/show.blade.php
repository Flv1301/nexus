@extends('adminlte::page')
@section('title','Base de Dados Caçador Roraima')
@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between col-md-12">
                <div>
                    <h1 class="h1 text-light">Base de Dados Caçador - Roraima</h1>
                </div>
                <div>
                    <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
                       type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('cacador.view.content')
@endsection

