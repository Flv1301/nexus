@extends('adminlte::page')
@section('title','Cadastro de Usuário')
@section('plugins.Select2', true)
@section('plugins.BootstrapSelect', true)
<x-page-header title="Atualização do Usuário {{$user->nickname}}"/>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('register.update', $user)}}" method="post" id="user-form">
                        @csrf
                        @method('PUT')
                        @include('auth.form')
                        <div class="card-footer mt-2">
                            <x-adminlte-button
                                type="submit"
                                label="Atualizar"
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
