@extends('adminlte::page')
@section('title','Pessoas')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)

@push('css')
<style>
.person-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
    transition: all 0.3s ease;
    cursor: pointer;
}

.person-photo:hover {
    border-color: #007bff;
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,123,255,0.3);
}

.photo-column {
    text-align: center;
    width: 60px;
    padding: 8px !important;
}

.no-photo-icon {
    color: #6c757d;
    font-size: 2em;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.no-photo-icon:hover {
    opacity: 1;
}
</style>
@endpush

@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex">
                <div class="mr-auto">
                    <h1 class="h1">Lista de Pessoas</h1>
                </div>
                <div>
                    <a id="register" href="{{route('person.create')}}" class="btn btn-success">Cadastrar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    @include('sweetalert::alert')
    <div class="card">
        <form method="GET" action="{{route('person.register.search')}}">
            @csrf
            <div class="card-header">Pesquisa Pessoa</div>
            <div class="card-body">
                <div class="d-flex">
                    <div class="col-md-4 form-group">
                        <x-adminlte-input name="search" placeholder="Pesquisa Por Nome, Alcunha, CPF, RG, Tatuagem."
                                          value="{{old('search') ?? $search ?? ''}}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div>
                        <x-adminlte-button type="submit" label="Pesquisar" theme="secondary"
                                           icon="fa fa-search"></x-adminlte-button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body">
            @php
                $heads = ['Código','Foto','Nome','Alcunha', 'Nascimento', 'CPF',['label' => 'Opções', 'no-export' => true, 'width' => 8]];
                $config = [
                    'order' => [[0, 'desc']],
                    'columns' => [null, ['orderable' => false], null, null, null, null, ['orderable' => false]],
                    'responsive' => true,
                    'searching' => false,
                    'lengthChange' => false,
                    'paging' => false,
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
                @foreach($persons as $person)
                    <tr>
                        <td>{{$person->id}}</td>
                        <td class="photo-column">
                            @if($person->hasImages())
                                @php $imageSrc = $person->getFirstImageThumbnail(); @endphp
                                @if($imageSrc)
                                    <img src="{{$imageSrc}}" 
                                         alt="Foto de {{$person->name}}" 
                                         class="person-photo img-thumbnail"
                                         title="{{$person->name}}"
                                         onclick="window.open('{{route('person.show', $person)}}', '_blank')">
                                @else
                                    <i class="fas fa-user-circle no-photo-icon" title="Foto não encontrada"></i>
                                @endif
                            @else
                                <i class="fas fa-user-circle no-photo-icon" title="Sem foto cadastrada"></i>
                            @endif
                        </td>
                        <td>{{substr($person->name,0,150)}}</td>
                        <td>{{$person->nickname}}</td>
                        <td>{{$person->birth_date}}</td>
                        <td>{{$person->cpf}}</td>
                        <td class="text-nowrap">
                            @can('pessoa.ler')
                            <a href="{{route('person.show', $person)}}" class='btn btn-sm mx-1' title='Visualizar Detalhes'>
                                <i class='fa fa-lg fa-fw fa-eye text-primary'></i>
                            </a>
                            @endcan
                            @can('pessoa.ler')
                            <a href="{{route('person.search.report', ['id' => $person->id])}}" class='btn btn-sm mx-1' title='Gerar Relatório PDF' target="_blank">
                                <i class='fa fa-lg fa-fw fa-file-pdf text-danger'></i>
                            </a>
                            @endcan
                            @can('pessoa.editar')
                            <a href="{{route('person.edit', $person->id)}}" class='btn btn-sm mx-1 text-warning' title='Editar'>
                                <i class='fa fa-lg fa-fw fa-edit'></i>
                            </a>
                            @endcan
                            @can('pessoa.excluir')
                            <form class="d-inline" action="{{route('person.destroy', ['id' => $person->id])}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm text-danger mx-1 delete-alert" title="Excluir">
                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
            <div class="d-flex justify-content-end mt-3">
                <div class="pagination">
                    {{ $persons->appends(['search' => $search ?? ''])->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Confirmação de exclusão para pessoa
        $('.delete-alert').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Excluir Pessoa',
                text: "Tem certeza que deseja excluir esta pessoa? Esta ação não pode ser desfeita e todos os dados relacionados serão removidos!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush

