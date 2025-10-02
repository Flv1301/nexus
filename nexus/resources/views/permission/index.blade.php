{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Lista de Tipo de Usuário')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
<x-page-header title="Lista de Permissões de Usuário"/>
@section('content')
    @include('sweetalert::alert')
    @include('permission.create')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'ID',
                    'Nome',
                    'Guarda',
                    ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                ];
                $config = [
                    'order' => [[0, 'desc']],
                    'columns' => [null, null, null, ['orderable' => false]]
                ];
            @endphp
            <x-adminlte-datatable id="tbl_permissions" :heads="$heads" :config="$config" striped hoverable>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{$permission->id}}</td>
                        <td>{{$permission->name}}</td>
                        <td>{{$permission->guard_name}}</td>
                        <td>
                            <div class="d-flex">
                                <form class="d-inline"
                                      action="{{route('permission.destroy', $permission)}}"
                                      method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm text-danger mx-1 delete-alert" title="Deletar">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@endsection
