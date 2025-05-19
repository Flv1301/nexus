@extends('adminlte::page')
@section('title','Cadastro de Usuário')
@section('plugins.Select2', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.BootstrapSwitch', true)
<x-page-header title="Cadastro de Usuário"/>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('register.store')}}" method="post">
                        @csrf
                        @include('auth.form')
                        <div class="card-footer mt-2">
                            <x-adminlte-button
                                type="submit"
                                label="Cadastrar"
                                theme="success"
                                icon="fas fa-sm fa-save"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
