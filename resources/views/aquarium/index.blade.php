@extends('adminlte::page')
@section('title', 'Aquarium')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('content')

            <div class="card">
                <div class="card-body">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $heads = [

                                    'Nome',

                                    'cpf',
                                    'endereço',
                                    'cortex',
                                 

                                ];
                                $config = [
                                    'order' => [[0, 'asc']],
                                    'columns' => [null, null, null,null],
                                    'language' => [
                                        'paginate' => [
                                            'first' => 'Primeiro',
                                            'last' => 'Último',
                                            'next' => 'Próximo',
                                            'previous' => 'Anterior',
                                        ],
                                         'buttons' => [
            'excel', 'csv', 'pdf', 'print'
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
                            <x-adminlte-datatable id="tbl_users" :heads="$heads" :config="$config" striped hoverable with-buttons>
                                @foreach($presos as $preso)
                                    <tr>

                                        <td>{{$preso->nome}}</td>

                                        <td>{{$preso->cpf}}</td>
                                        <td>{{$preso->endereco}}</td>
                                        <td><a href="/pesquisa/pessoa/cortex/{{$preso->cpf}}" target="_blank">cortex</a></td>





                                    </tr>

                                @endforeach
                            </x-adminlte-datatable>
                        </div>
                    </div>
                </div>
            </div>

@endsection

