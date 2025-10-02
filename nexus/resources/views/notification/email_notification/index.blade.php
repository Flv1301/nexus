{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('title','Lista de Notificados')
@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex">
                <div class="mr-auto">
                    <h1 class="h1">Lista de Notificados</h1>
                </div>
                <div>
                    <a href="{{route('email_notification.create')}}" class="btn btn-success">Cadastrar</a>
                </div>
            </div>
        </div>
        @endsection
        @section('content')
            @include('sweetalert::alert')
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'ID',
                            'Nome',
                            'Email',
                            'Tipo',
                            ['label' => 'Actions', 'no-export' => true, 'width' => 1],
                        ];
                        $config = [
                            'order' => [[0, 'asc']],
                            'columns' => [null, null, null, null, ['orderable' => false]],
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
                    <x-adminlte-datatable id="tbl_cases" :heads="$heads" :config="$config" striped hoverable>
                        @foreach($usersNotifications as $notification)
                            <tr>
                                <td>{{$notification->id}}</td>
                                <td>{{$notification->user->name}}</td>
                                <td>{{$notification->user->email}}</td>
                                <td>{{$notification->type}}</td>
                                <td>
                                    <form class="d-inline"
                                          action="{{route('email_notification.destroy', $notification)}}"
                                          method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm text-danger mx-1 delete-alert" title="Delete">
                                            <i class="fa fa-lg fa-fw fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
@endsection
