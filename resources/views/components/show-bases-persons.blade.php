@props(['bases', 'route'])
@php
    $heads = [
        'Base',
        'Nome',
        'CPF',
        'Mãe',
        'Nascimento',
        ['label' => 'Opções', 'no-export' => true, 'width' => 5],
    ];
    $config = [
        'order' => [[1, 'asc']],
        'columns' => [null, null, null, null, null, null, ['orderable' => false]],
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
<div class="card">
    <div class="card-body">
        <x-adminlte-datatable id="tbl_persons_search" :heads="$heads" :config="$config" striped hoverable>
            @foreach($bases as $key => $base)
                @if($base->count() > 0)
                    @foreach($base as $person)
                        <tr>
                            <td>{{ $key === 'person' ? 'HYDRA' : \Illuminate\Support\Str::upper($key)}}</td>
                            <td>{{$person->name}}</td>
                            <td>{{$person->cpf ?? ''}}</td>
                            <td>{{$person->mother ?? ''}}</td>
                            <td>{{$person->birth_date ?? ''}}</td>
                            <td><a href="{{route($route, ['base'=> $key, 'id' => $person->id])}}">
                                    <i class="fas fa-lg fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
