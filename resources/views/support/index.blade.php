{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 06/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Suporte')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex">
                <div class="mr-auto">
                    <h1 class="h1">Suportes</h1>
                </div>
                <div>
                    <a id="register" href="{{route('support.create')}}" class="btn btn-success">Cadastrar</a>
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
                            'Titulo',
                            'Usuário',
                            'Data',
                            'Status',
                            ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                        ];
                        $config = [
                            'order' => [[0, 'desc']],
                            'columns' => [null, null, null, null, null, ['orderable' => false]],
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
                    <x-adminlte-datatable id="tbl_supports" :heads="$heads" :config="$config" striped hoverable>
                        @foreach($supports as $support)
                            <tr>
                                <td>{{$support->id}}</td>
                                <td>{{$support->title}}</td>
                                <td>{{$support->user->nickname}}</td>
                                <td>{{$support->created_at->format('d/m/Y H:i')}}</td>
                                <td><span
                                        class="{{\App\Enums\StatusSupportEnum::from($support->status)->style()}}">{{$support->status}}</span>
                                </td>
                                <td>
                                    <nobr/>
                                    <a href="{{route('support.show', $support)}}"
                                       class='btn btn-sm mx-1'
                                       title='Detalhe'>
                                        <i class='fa fa-lg fa-fw fa-eye'></i>
                                    </a>
                                    <nobr/>
{{--                                    <a href="{{route('support.edit', $support)}}"--}}
{{--                                       class='btn btn-sm mx-1 text-primary'--}}
{{--                                       title='Edição'>--}}
{{--                                        <i class='fa fa-lg fa-fw fa-edit'></i>--}}
{{--                                    </a>--}}
                                    <nobr/>
                                    {{--                                    <form class="d-inline"--}}
                                    {{--                                          action="{{route('support.destroy', $support)}}"--}}
                                    {{--                                          method="post">--}}
                                    {{--                                        @csrf--}}
                                    {{--                                        @method('DELETE')--}}
                                    {{--                                        <button--}}
                                    {{--                                            type="submit"--}}
                                    {{--                                            class="btn btn-sm text-danger mx-1 delete-alert"--}}
                                    {{--                                            title="Delete">--}}
                                    {{--                                            <i class="fa fa-lg fa-fw fa-trash"></i>--}}
                                    {{--                                        </button>--}}
                                    {{--                                    </form>--}}
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
@endsection
