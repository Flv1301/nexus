{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/03/2023
 * @copyright NIP CIBER-LAB @2023
--}}
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
    <div class="card">
        <div class="card-header">
            <span>Documentos</span>
        </div>
        <div class="card-body">
            @include('perfil.documents')
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span>Assinatura</span>
        </div>
        <div class="card-body text-center">
            <div
                style="display: inline-block; width: 150px; height: 100px; border: 1px solid #ccc; margin-bottom: 10px;">
                @if ($user->signature)
                    @php
                        $type = \Illuminate\Support\Facades\Storage::mimeType($user->signature);
                        $content = \Illuminate\Support\Facades\Storage::get($user->signature);
                        $content = base64_encode($content);
                    @endphp
                    <img src="data:{{$type}};base64,{{$content}}" alt="Assinatura"
                         style="width: 100%; height: auto; object-fit: cover;"/>
                @else
                    <Typography variant="body2">Sem Assinatura</Typography>
                @endif
            </div>
            <button class="btn bg-light" data-toggle="modal" data-target="#signatureModal">Upload Assinatura</button>
        </div>
    </div>

    <div class="modal fade" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signatureModalLabel">Modificar Assinatura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('perfil.ass', ['id' => $user->id]) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="file" name="signature" accept="image/*" required/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
