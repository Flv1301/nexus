@props(['bases', 'route'])

@push('css')
<style>
.person-photo {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.person-photo:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,123,255,0.3);
}

.photo-column {
    text-align: center;
    width: 70px;
    padding: 8px !important;
}

.no-photo-icon {
    color: #6c757d;
    font-size: 45px;
    opacity: 0.7;
    transition: opacity 0.3s ease;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.no-photo-icon:hover {
    opacity: 1;
}
</style>
@endpush

@php
    $heads = [
        'Base',
        'Foto',
        'Nome',
        'CPF',
        'Mãe',
        ['label' => 'Opções', 'no-export' => true, 'width' => 15],
    ];
    $config = [
        'order' => [[2, 'asc']],
        'columns' => [null, ['orderable' => false], null, null, null, ['orderable' => false]],
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
                            <td class="photo-column">
                                @if(($key === 'nexus' || $key === 'faccionado') && method_exists($person, 'hasImages') && $person->hasImages())
                                    @php $imageSrc = $person->getFirstImageThumbnail(); @endphp
                                    @if($imageSrc)
                                        <img src="{{$imageSrc}}" 
                                             alt="Foto de {{$person->name}}" 
                                             class="person-photo"
                                             title="{{$person->name}}">
                                    @else
                                        <i class="fas fa-user-circle no-photo-icon" title="Foto não encontrada"></i>
                                    @endif
                                @else
                                    <i class="fas fa-user-circle no-photo-icon" title="{{($key === 'nexus' || $key === 'faccionado') ? 'Sem foto cadastrada' : 'Base externa'}}"></i>
                                @endif
                            </td>
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
