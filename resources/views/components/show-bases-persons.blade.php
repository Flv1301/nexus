@props(['bases', 'route'])
@php
    $heads = [
        'Base',
        'Nome',
        'CPF',
        'Mãe',
        ['label' => 'Opções', 'no-export' => true, 'width' => 15],
    ];
    $config = [
        'order' => [[1, 'asc']],
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
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($key === 'faccionado')
                                        {{-- Para dados faccionados, redireciona para visualização normal da pessoa --}}
                                        <a href="{{route('person.show', $person->id)}}" class="mr-2" title="Visualizar Dados">
                                            <i class="fas fa-lg fa-eye text-primary"></i>
                                        </a>
                                    @else
                                        {{-- Para outras bases, usa a rota de busca normal --}}
                                        <a href="{{route($route, ['base'=> $key, 'id' => $person->id])}}" class="mr-2" title="Visualizar Dados">
                                            <i class="fas fa-lg fa-eye text-primary"></i>
                                        </a>
                                    @endif
                                    @if($key === 'person' || $key === 'nexus' || $key === 'faccionado')
                                    <a href="{{route('person.search.report', ['id' => $person->id])}}" title="Gerar Relatório PDF" target="_blank">
                                        <i class="fas fa-lg fa-file-pdf text-danger"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
