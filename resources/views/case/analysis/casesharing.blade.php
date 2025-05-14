{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 16/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-header">
        <span class="text-black text-lg">Lista de Usuários</span>
    </div>
    <div class="card-body">
        @php
            $heads = ['ID','Nome','Matrícula'];
            $config = [
                'order' => [[0, 'asc']],
                'columns' => [null, null, null],
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
        <x-adminlte-datatable id="tab_users" :heads="$heads" striped hoverable>
            @foreach($case->users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->nickname }}</td>
                    <td>{{ $user->registration }}</td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <span class="text-black text-lg">Lista de Setores</span>
    </div>
    <div class="card-body">
        @php
            $heads = ['ID','Setor','Unidade'];
//            $config = ['order' => [[0, 'asc']],'columns' => [null, null, null]];
        @endphp
        <x-adminlte-datatable id="tab_case_sectors" :heads="$heads" striped hoverable>
            @foreach($case->sectors as $sector)
                <tr>
                    <td>{{ $sector->id }}</td>
                    <td>{{ $sector->name }}</td>
                    <td>{{ $sector->unity->name }}</td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <span class="text-black text-lg">Lista de Unidades</span>
    </div>
    <div class="card-body">
        @php
            $heads = ['ID','Unidade'];
            $config = ['order' => [[0, 'asc']],'columns' => [null, null]];
        @endphp
        <x-adminlte-datatable id="tab_case_unitys" :heads="$heads" striped hoverable>
            @foreach($case->unitys as $unity)
                <tr>
                    <td>{{ $unity->id }}</td>
                    <td>{{ $unity->name }}</td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
