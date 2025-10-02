{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 13/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Lista de Tipo de Usuário')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
<x-page-header title="Atualização de Permissões de Usuário"/>
@section('content')
    @include('sweetalert::alert')
    <div class="card">
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('permission.update', $permission)}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            @include('permission.form')
                            <div class="form-group d-flex align-items-center mt-3">
                                <x-adminlte-button
                                    type="submit"
                                    label="Atualizar"
                                    theme="success"
                                    icon="fas fa-sm fa-save"
                                />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
