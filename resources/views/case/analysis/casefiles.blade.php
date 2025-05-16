<div class="card">
    <div class="card-body">
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
        <x-adminlte-datatable id="files" :heads="$heads" striped hoverable :config="$config">
            @foreach($files as $key => $file)
                <tr>
                    <td>{{ $file->alias }}</td>
                    <td>{{ $file->name }}</td>
                    <td>{{ $file->file_alias }}</td>
                    <td>{{ \Illuminate\Support\Str::upper($file->extension) }}</td>
                    <td>{{ $file->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{route('case.file', ['id' => $key])}}" class='btn btn-sm mx-1' title='Detalhe'>
                                <i class='fa fa-lg fa-fw fa-eye'></i>
                            </a>
                            @if($file->user_id == Auth::id() && $file->procedure == 0 && $case->status !== 'CONCLUIDO')
                                <form action="{{route('case.file.destroy', ['id' => $key])}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm text-danger mx-1 delete-alert" title="Delete">
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
</div>
