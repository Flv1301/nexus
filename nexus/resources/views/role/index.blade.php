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
@section('plugins.BootstrapSelect', true)
<x-page-header title="Lista de Funções de Usuário"/>
@section('content')
    @include('sweetalert::alert')
    @include('role.create')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Nome',
                    'Guarda',
                    ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                ];
                $config = [
                    'order' => [[0, 'asc']],
                    'columns' => [null, null, ['orderable' => false]],
                    'language' => [
                        'paginate' => [
                            'first' => 'Primeiro',
                            'last' => 'Último',
                            'next' => 'Próximo',
                            'previous' => 'Anterior',
                        ],
                        'search' => 'Pesquisar na Tabela',
                        'lengthMenu'=>    "Mostrar  _MENU_  Resultados",
                        'info'=>           "Mostrando _START_ a _END_ de _TOTAL_ Resultados.",
                        'infoEmpty'=>      "Mostrando 0 Resultados.",
                        'infoFiltered'=>   "(Filtro de _MAX_ Resultados no total)",
                        'loadingRecords'=> "Pesquisando...",
                        'zeroRecords'=>    "Nem um dado(s) encontrado(s)",
                        'emptyTable'=>     "Sem dados!",
                    ],
                ];
            @endphp
            <x-adminlte-datatable
                id="tbl_roles"
                :heads="$heads"
                :config="$config"
                striped hoverable>
                @foreach($roles as $role)
                    <tr>
                        <td>{{$role->name}}</td>
                        <td>{{$role->guard_name}}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{route('role.show', $role)}}"
                                   class='btn btn-sm mx-1' title='Detalhe'
                                >
                                    <i class='fa fa-lg fa-fw fa-eye'></i>
                                </a>
                                <a href="{{route('role.edit', ['id' => $role->id])}}"
                                   class='btn btn-sm mx-1 text-primary' title='Edição'
                                >
                                    <i class='fa fa-lg fa-fw fa-edit'></i>
                                </a>
                                <form class="d-inline"
                                      action="{{route('role.destroy', ['id' => $role->id])}}"
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
