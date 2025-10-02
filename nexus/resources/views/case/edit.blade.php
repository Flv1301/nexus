@extends('adminlte::page')
@section('title',"Caso " . $case->name)
@section('plugins.Select2', true)
@section('plugins.BsCustomFileinput', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Summernote', true)
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
<x-page-header title="Edição do Movimento {{\Illuminate\Support\Str::title($case->name)}}">
    <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
       type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card card-warning card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#tab_data" data-toggle="pill" aria-controls="tab_data" role="tab"
                       class="nav-link active">Dados</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_person" data-toggle="pill" aria-controls="tab_person" role="tab"
                       class="nav-link">Alvos</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_sharing" data-toggle="pill" aria-controls="tab_sharing" role="tab"
                       class="nav-link">Compartilhamento</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_files" data-toggle="pill" aria-controls="tab_files" role="tab"
                       class="nav-link">Arquivos</a>
                </li>
            </ul>
        </div>
        <form action="{{ route('case.update', $case) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_data">
                        @include('case.store.data')
                    </div>
                    <div class="tab-pane" id="tab_person" role="tabpanel">
                        @include('case.store.person')
                    </div>
                    <div class="tab-pane" id="tab_sharing" role="tabpanel">
                        @include('case.store.sharing')
                    </div>
                    <div class="tab-pane" id="tab_files" role="tabpanel">
                        @include('case.store.files')
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <x-adminlte-button type="submit" label="Atualizar"
                                   theme="success"
                                   icon="fas fa-lg fa-save"/>
            </div>
        </form>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        let fileIndex = 1;

        // Adicionar novo campo de arquivo
        $('#add-file-btn').on('click', function() {
            const newFileRow = `
                <div class="file-upload-row" data-index="${fileIndex}">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h6 class="card-title">
                                <i class="fas fa-file"></i> Arquivo ${fileIndex + 1}
                            </h6>
                            <div class="card-tools">
                                <button type="button" class="btn btn-sm btn-danger remove-file-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file_names_${fileIndex}">Alias do Arquivo (Opcional)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                            </div>
                                            <input class="form-control" style="text-transform:uppercase" name="file_names[${fileIndex}]" 
                                                   placeholder="Nome personalizado para o arquivo" id="file_names_${fileIndex}" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file_types_${fileIndex}">Tipo do Arquivo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-file-import"></i></span>
                                            </div>
                                            <select class="form-control" name="file_types[${fileIndex}]" id="file_types_${fileIndex}">
                                                <option value="document">Documento Padrão</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="case_files_${fileIndex}">Selecionar Arquivo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-upload"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="case_files[${fileIndex}]" id="case_files_${fileIndex}">
                                                <label class="custom-file-label" for="case_files_${fileIndex}">Escolha um arquivo para anexar</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#file-upload-container').append(newFileRow);
            fileIndex++;
            updateRemoveButtons();
        });

        // Remover campo de arquivo
        $(document).on('click', '.remove-file-btn', function() {
            $(this).closest('.file-upload-row').remove();
            updateFileNumbers();
            updateRemoveButtons();
        });

        // Atualizar numeração dos arquivos
        function updateFileNumbers() {
            $('.file-upload-row').each(function(index) {
                $(this).find('.card-title').html(`<i class="fas fa-file"></i> Arquivo ${index + 1}`);
                $(this).attr('data-index', index);
            });
        }

        // Mostrar/esconder botões de remover
        function updateRemoveButtons() {
            const fileRows = $('.file-upload-row');
            if (fileRows.length > 1) {
                $('.remove-file-btn').show();
            } else {
                $('.remove-file-btn').hide();
            }
        }

        // Atualizar nome do arquivo quando selecionado
        $(document).on('change', '.custom-file-input', function() {
            const fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });

        // Alerta de confirmação para exclusão de arquivos existentes
        $('.delete-alert').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
