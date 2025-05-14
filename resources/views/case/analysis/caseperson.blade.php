{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 16/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        @php
            $heads = [
                'ID',
                'Nome',
                'Alcunha',
                'CPF',
            ];

            $config = [
                'order' => [[1, 'asc']],
                'columns' => [null, null, null, null],
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
        <x-adminlte-datatable id="persons" :heads="$heads" striped hoverable>
            @foreach($case->persons as $person)
                <tr>
                    <td>{{ $person->id }}</td>
                    <td>{{ $person->name }}</td>
                    <td>{{ $person->nickname }}</td>
                    <td>{{ $person->cpf }}</td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
