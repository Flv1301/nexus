@php use App\Helpers\MapsHerlper; @endphp
{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 08/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title', 'GI2')
@section('plugins.Datatables', true)
@include('gi2.header')
@section('content')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Data',
                    'Operação',
                    'IMEI',
                    'IMSI',
                    'Operadora',
                    'BOP',
                    'Data',
                    'Tipo',
                    ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                ];
                $config = [
                    'order' => [[0, 'desc']],
                    'columns' => [null,null, null, null, null, null, null, null, ['orderable' => false]],
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
            <x-adminlte-datatable id="tbl_gi2_intersection" :heads="$heads" :config="$config" striped hoverable with-buttons>
                @foreach($gi2s as $gi2)
                    <tr>
                        <td>{{ \Illuminate\Support\Carbon::parse($gi2->date_time)->format('d/m/Y H:i') }}</td>
                        <td>{{ $gi2->operacao }}</td>
                        <td>{{ $gi2->imei }}</td>
                        <td>{{ $gi2->imsi }}</td>
                        <td>{{ MapsHerlper::coordinateConverterGi2((string)$gi2->latitude) }}</td>
                        <td>{{ MapsHerlper::coordinateConverterGi2((string)$gi2->longitude) }}</td>

                        <td>{{ $gi2->operador }}</td>
                        <td>{{ $gi2->n_bop }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($gi2->dt_registro)->format('d/m/Y H:i') }}</td>
                        <td>{{ $gi2->registros }}</td>
                        <td>
                            <a href="{{route('gi2.show', $gi2->id)}}">
                                <i class="fas fa-wifi fa-lg" style="color: #00a87d" title="GI2"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@endsection

