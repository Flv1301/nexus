@extends('adminlte::page')
@section('title',"Usuário {$user->nickname}")
<x-page-header title="Usuário {{$user->nickname}}"/>
@section('content')
    <div class="card">
        <div class="card-header">
            <span>Informações Pessoas</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <x-adminlte-input
                        name="name"
                        label="Nome Completo"
                        disabled
                        value="{{$user->name}}"
                    />
                </div>
                <div class="form-group col-md-4">
                    <x-adminlte-input
                        name="nickname"
                        label="Nome Apresentação"
                        disabled
                        value="{{$user->nickname}}"
                    />
                </div>
                <div class="form-group col-md-2">
                    <x-adminlte-input
                        name="registration"
                        label="Matricula"
                        disabled
                        value="{{$user->registration}}"
                    />
                </div>
                <div class="form-group col-md-2">
                    <x-adminlte-input
                        name="cpf"
                        label="CPF"
                        disabled
                        value="{{$user->cpf}}"
                    />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <x-adminlte-input
                        name="office"
                        label="Cargo"
                        disabled
                        value="{{$user->office}}"
                    />
                </div>
                <div class="form-group col-md-4">
                    <x-adminlte-input class="mask-date"
                                      name="birth_date"
                                      label="Data Nascimento"
                                      disabled
                                      value="{{$user->birth_date ?? ''}}"
                    />
                </div>
                <div class="col-md-1">
                    <x-adminlte-input
                        name="ddd"
                        label="DDD"
                        class="mask-ddd"
                        disabled
                        value="{{$user->ddd ?? ''}}"
                    />
                </div>
                <div class="form-group col-md-3">
                    <x-adminlte-input
                        name="telephone"
                        label="Telefone"
                        class="mask-phone"
                        disabled
                        value="{{$user->telephone ?? ''}}"
                    />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <x-adminlte-input
                        name="sector_id"
                        label="Unidade"
                        disabled
                        value="{{$user->unity->name}}"
                    />
                </div>
                <div class="form-group col-md-5">
                    <x-adminlte-input
                        name="unity_id"
                        label="Setor"
                        disabled
                        value="{{$user->sector->name}}"
                    />
                </div>
                <div class="form-group col-md-1">
                    <x-adminlte-input
                        name="coordinator"
                        label="Coordenador"
                        disabled
                        value="{{$user->coordinator ? 'SIM' : 'NÃO'}}"
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span>Dados de Acesso</span>
        </div>
        <div class="card-body">
            <div class="form-row d-flex">
                <div class="form-group col-md-6">
                    <x-adminlte-input
                        name="email"
                        label="E-Mail"
                        disabled
                        value="{{$user->email}}"
                    />
                </div>
                <div class="form-group mt-2">
                    <a href="{{route('perfil.password')}}" class="btn btn-info mt-4">Alterar Senha</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span>Endereço</span>
        </div>
        <div class="card-body">
            @include('address.show')
        </div>
    </div>
@endsection
