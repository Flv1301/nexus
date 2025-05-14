{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Função de Usuário')
@section('plugins.BootstrapSelect', true)
@section('plugins.Datatables', true)
<x-page-header title="Função de Usuário"/>
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <span class="text-lg">Função: {{$role->name}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="text-info ml-2">Permissões</span>
                    <div class="card">
                        <div class="card-body">
                            @php
                                $heads = [
                                    'ID',
                                    'Nome',
                                    'Guarda'
                                ];
                                $config = [
                                    'order' => [[0, 'desc']],
                                    'columns' => [null, null, null]
                                ];
                            @endphp
                            <x-adminlte-datatable id="tbl_permissions"
                                                  :heads="$heads"
                                                  :config="$config"
                                                  striped hoverable>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>{{$permission->id}}</td>
                                        <td>{{$permission->name}}</td>
                                        <td>{{$permission->guard_name}}</td>
                                    </tr>
                                @endforeach
                            </x-adminlte-datatable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

