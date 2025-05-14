{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title', 'Pesquisa de IMEI')
@section('plugins.Datatables', true)
<x-page-header title="Pesquisa de IMEI"/>
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{route('imei.search')}}" method="post">
                @csrf
                <div class="d-flex align-items-center">
                    <div class="col-md-4">
                        <x-adminlte-input name="imei" label="IMEI" value="{{old('imei')}}"/>
                    </div>
                    <div class="mt-3">
                        <x-adminlte-button label="Pesquisar" type="submit" theme="secondary" icon="fas fa-search"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @isset($imeis)
        @if($imeis->count())
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'IMEI',
                            'BOP',
                            ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                        ];
                        $config = [
                            'order' => [[0, 'desc']],
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
                    <x-adminlte-datatable id="tbl_persons" :heads="$heads" :config="$config" striped hoverable>
                        @foreach($imeis as $imei)
                            <tr>
                                <td>{{$imei->imei}}</td>
                                <td>{{$imei->n_bop}}</td>
                                <td>
                                    <div class="d-flex justify-content-between">
                                        <a href="{{route('imei.show', $imei)}}" title="Visualizar"><i
                                                class="fas fa-lg fa-eye"></i></a>
                                        @if($imei->gi2->count())
                                            <a href="{{route('imei.gi2', $imei)}}" title="GI2"><i
                                                    class="fas fa-lg fa-wifi" style="color: #11921a;"></i></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <span class="text-lg text-info"> <i class="fas fa-info-circle fa-lg"></i> Sem registro na base de dados para o dado pesquisado!</span>
                </div>
            </div>
        @endif
    @endisset
@endsection
