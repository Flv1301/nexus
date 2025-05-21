@extends('adminlte::page')
@section('title','Cadastro de Pessoa')
@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between col-md-12">
                <div>
                    <h1 class="h1 text-light">Cadastro de Pessoa</h1>
                </div>
                <div>
                    @if($person->warrant == 1 && !$person->active_orcrim)
                        <span class="text-danger text-lg p-1 pr-4 pl-4 rounded" style="background-color: #FFC720">
                        Atenção: Pessoa com mandado de prisão - Consultar na base nacional.
                    </span>
                    @endif
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
    @include('person.view.content')
@endsection
