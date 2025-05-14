{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@if($gi2s->count() == 1)
    @include('gi2.view')
@else
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'ID',
                    'Data',
                    'IMEI',
                    'IMSI',
                    'Operadora',
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
            <x-adminlte-datatable id="tbl_gi2" :heads="$heads" :config="$config" striped hoverable with-buttons>
                @foreach($gi2s as $gi2)
                    <tr>
                        <td>{{ $gi2->id }}</td>
                        <td>{{ $gi2->date_time }}</td>
                        <td>{{ $gi2->imei }}</td>
                        <td>{{ $gi2->imsi }}</td>
                        <td>{{ $gi2->transmission_operator }}</td>
                        <td>
                            <a href="{{route('gi2.show', $gi2)}}">
                                <i class="fas fa-wifi fa-lg" style="color: #00a87d" title="GI2"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@endif
