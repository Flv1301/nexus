{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Atualização de Setor')
@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex">
                <div class="mr-auto">
                    <h1 class="h1">Atualização de Setor.</h1>
                </div>
            </div>
        </div>
        @endsection
        @section('content')
            @include('sweetalert::alert')
            <div class="card">
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('sector.update', ['id' => $sector->id])}}" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <x-adminlte-select2
                                            id="unity_id"
                                            name="unity_id"
                                            label="Unidade"
                                            placeholder="Selecione uma unidade."
                                        >
                                            <option/>
                                            @foreach($unitys as $unity)
                                                <option
                                                    value="{{$unity->id}}"
                                                    @if($unity->id == $sector->unity_id) selected @endif
                                                >
                                                    {{$unity->name}}
                                                </option>
                                            @endforeach
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text text-black">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-select2>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <x-adminlte-input
                                            name="name"
                                            label="Nome do Setor"
                                            placeholder="Digite o nome do setor."
                                            value="{{$sector->name}}"

                                        />
                                    </div>
                                </div>
                                <div class="card-footer">
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
