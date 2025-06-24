@extends('adminlte::page')

@section('title', 'Análise WhatsApp')

@section('content_header')
    <h1><i class="fab fa-whatsapp text-success"></i> Análise de Arquivos WhatsApp</h1>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-upload"></i> Upload de Arquivo WhatsApp</h3>
        </div>
        
        <form action="{{ route('whatsapp.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="analysis_name">
                                <i class="fas fa-tag"></i> Nome da Análise
                            </label>
                            <input type="text" 
                                   class="form-control @error('analysis_name') is-invalid @enderror" 
                                   id="analysis_name" 
                                   name="analysis_name" 
                                   placeholder="Ex: Análise WhatsApp - Caso 123"
                                   value="{{ old('analysis_name') }}" 
                                   required>
                            @error('analysis_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="whatsapp_file">
                                <i class="fas fa-file-code"></i> Arquivo WhatsApp (.html)
                            </label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" 
                                           class="custom-file-input @error('whatsapp_file') is-invalid @enderror" 
                                           id="whatsapp_file" 
                                           name="whatsapp_file" 
                                           accept=".html,.htm"
                                           required>
                                    <label class="custom-file-label" for="whatsapp_file">Escolher arquivo...</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fab fa-html5"></i></span>
                                </div>
                            </div>
                            @error('whatsapp_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Arquivo HTML exportado do WhatsApp (máximo 10MB)
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-cogs"></i> Processar Arquivo
                </button>
            </div>
        </form>
    </div>

    <!-- Card informativo -->
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Funcionalidades da Análise</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-globe"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Resolução de IPs</span>
                            <span class="info-box-number">Geolocalização</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Análise Temporal</span>
                            <span class="info-box-number">DateTime</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-phone"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Números</span>
                            <span class="info-box-number">Telefones</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="info-box bg-gradient-danger">
                        <span class="info-box-icon"><i class="fas fa-network-wired"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Porta Lógica</span>
                            <span class="info-box-number">Conexões</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    // Atualizar nome do arquivo quando selecionado
    $('#whatsapp_file').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@endsection 