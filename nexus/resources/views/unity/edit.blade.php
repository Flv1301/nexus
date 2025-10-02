@extends('adminlte::page')
@section('title','Cadastro de Unidade')
@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex">
                <div class="mr-auto">
                    <h1 class="h1">Cadastro de Unidade.</h1>
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
                            <form action="{{route('unity.update', $unity)}}" method="post">
                                @csrf
                                @method('PUT')
                                @include('unity.form')
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
