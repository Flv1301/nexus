@section('plugins.Datatables', true)

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Gerenciar Arquivos</h3>
    </div>
    <div class="card-body">
        @if(isset($case) && $case->id && isset($files) && count($files) > 0)
            {{-- Exibir arquivos existentes apenas se o caso já foi salvo e tem arquivos --}}
            @php
                $heads = [
                    'Alias',
                    'Nome do Arquivo',
                    'Tipo',
                    'Extensão',
                    'Data Upload',
                    ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                ];
                $config = [
                    'order' => [[4, 'desc']],
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
            <div class="mb-3">
                <h5>Arquivos Existentes</h5>
                <x-adminlte-datatable id="files_table" :heads="$heads" striped hoverable :config="$config">
                    @foreach($files as $key => $file)
                        <tr>
                            <td>{{ $file->alias ?? 'N/A' }}</td>
                            <td>{{ $file->name }}</td>
                            <td>{{ $file->file_alias ?? 'Documento' }}</td>
                            <td>{{ \Illuminate\Support\Str::upper($file->extension ?? '') }}</td>
                            <td>{{ $file->created_at ? $file->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{route('case.file', ['id' => $key])}}" class='btn btn-sm mx-1' title='Visualizar'>
                                        <i class='fa fa-lg fa-fw fa-eye'></i>
                                    </a>
                                    @if($file->user_id == Auth::id() && ($file->procedure ?? 0) == 0 && $case->status !== 'CONCLUIDO' && $case->status !== 'ARQUIVADO')
                                        <form action="{{route('case.file.destroy', ['id' => $key])}}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm text-danger mx-1 delete-alert" title="Excluir">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-adminlte-datatable>
            </div>
            <hr>
        @endif

        {{-- Seção para adicionar novos arquivos --}}
        <div class="row">
            <div class="col-md-12">
                <h5>
                    <i class="fas fa-plus-circle"></i> 
                    @if(isset($case) && $case->id)
                        Adicionar Mais Arquivos
                    @else
                        Adicionar Arquivos ao Caso
                    @endif
                </h5>
                <p class="text-muted">Você pode adicionar múltiplos arquivos que serão anexados ao caso.</p>
            </div>
        </div>

        <div id="file-upload-container">
            <div class="file-upload-row" data-index="0">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h6 class="card-title">
                            <i class="fas fa-file"></i> Arquivo 1
                        </h6>
                        <div class="card-tools">
                            <button type="button" class="btn btn-sm btn-danger remove-file-btn" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-adminlte-input
                                    name="file_names[0]"
                                    style="text-transform:uppercase"
                                    label="Alias do Arquivo (Opcional)"
                                    placeholder="Nome personalizado para o arquivo"
                                    value="{{ old('file_names.0') }}">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">
                                            <i class="fas fa-signature"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                            </div>
                            <div class="col-md-6">
                                <x-adminlte-select2 name="file_types[0]" label="Tipo do Arquivo" value="{{ old('file_types.0', 'document') }}">
                                    <option value="document">Documento Padrão</option>
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">
                                            <i class="fas fa-file-import"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-select2>
                            </div>
                            <div class="col-md-12">
                                <x-adminlte-input-file 
                                    name="case_files[0]"
                                    label="Selecionar Arquivo"
                                    placeholder="Escolha um arquivo para anexar">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">
                                            <i class="fas fa-upload"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input-file>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <button type="button" class="btn btn-outline-primary" id="add-file-btn">
                    <i class="fas fa-plus"></i> Adicionar Mais um Arquivo
                </button>
            </div>
        </div>

        @if(isset($case) && $case->id && !isset($files))
            <div class="alert alert-secondary mt-3">
                <i class="fas fa-folder-open"></i>
                <strong>Nenhum arquivo:</strong> Este caso ainda não possui arquivos anexados.
            </div>
        @endif
    </div>
</div> 