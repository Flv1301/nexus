{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 03/03/2023
 * @copyright NIP CIBER-LAB @2023
--}}
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
{{--                    @if($person->active_orcrim && !auth()->user()->can('sisfac'))--}}
{{--                        <span class="text-danger text-lg p-1 pr-4 pl-4 rounded">--}}
{{--                        Algumas informações são restritas ao perfil SISFAC. Para mais informações, entrar em contato com GTF (NIP).--}}
{{--                    </span>--}}
{{--                        @include('sisfac_block')--}}
{{--                    @endif--}}
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

