@extends('adminlte::page')

@section('title', 'Resultado da Análise WhatsApp')

@section('content_header')
    <h1><i class="fab fa-whatsapp text-success"></i> {{ $analysis['data']['name'] ?? 'Análise WhatsApp' }}</h1>
@endsection

@section('content')
    @if(!$analysis)
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> Análise não encontrada.
        </div>
        <a href="{{ route('whatsapp.analysis') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
        @php return; @endphp
    @endif

    <!-- Estatísticas gerais -->
    <div class="row">
        <div class="col-md-3">
            <div class="info-box bg-info">
                <span class="info-box-icon"><i class="fas fa-comments"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total de Mensagens</span>
                    <span class="info-box-number">{{ $analysis['data']['stats']['total_messages'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-globe"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">IPs Únicos</span>
                    <span class="info-box-number">{{ $analysis['data']['stats']['unique_ips'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-phone"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Telefones Únicos</span>
                    <span class="info-box-number">{{ $analysis['data']['stats']['unique_phones'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Período</span>
                    <span class="info-box-number">
                        @if(isset($analysis['data']['stats']['date_range']))
                            {{ count(explode(' - ', $analysis['data']['stats']['date_range']['start'] . ' - ' . $analysis['data']['stats']['date_range']['end'])) > 1 ? 'Múltiplos' : 'Único' }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Período de análise -->
    @if(isset($analysis['data']['stats']['date_range']))
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Período da Conversa</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong><i class="fas fa-play"></i> Início:</strong> 
                    {{ $analysis['data']['stats']['date_range']['start'] }}
                </div>
                <div class="col-md-6">
                    <strong><i class="fas fa-stop"></i> Fim:</strong> 
                    {{ $analysis['data']['stats']['date_range']['end'] }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- IPs encontrados -->
    @if(!empty($analysis['data']['ips']))
    <div class="card card-success">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-globe"></i> IPs Identificados</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><i class="fas fa-network-wired"></i> IP</th>
                            <th><i class="fas fa-map-marker-alt"></i> Localização</th>
                            <th><i class="fas fa-building"></i> Provedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($analysis['data']['ips'] as $ip)
                        <tr>
                            <td><code>{{ $ip }}</code></td>
                            <td>
                                <i class="fas fa-spinner fa-spin text-muted"></i> 
                                <span class="text-muted">Carregando...</span>
                            </td>
                            <td>
                                <i class="fas fa-spinner fa-spin text-muted"></i> 
                                <span class="text-muted">Carregando...</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Telefones encontrados -->
    @if(!empty($analysis['data']['phones']))
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-phone"></i> Números de Telefone</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($analysis['data']['phones'] as $phone)
                <div class="col-md-4 mb-2">
                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-mobile-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Telefone</span>
                            <span class="info-box-number">{{ $phone }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Timestamps -->
    @if(!empty($analysis['data']['timestamps']))
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clock"></i> Timestamps Identificados</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(array_slice($analysis['data']['timestamps'], 0, 20) as $timestamp)
                        <tr>
                            <td><i class="fas fa-clock text-primary"></i> {{ $timestamp }}</td>
                        </tr>
                        @endforeach
                        @if(count($analysis['data']['timestamps']) > 20)
                        <tr>
                            <td class="text-muted">
                                <i class="fas fa-ellipsis-h"></i> 
                                E mais {{ count($analysis['data']['timestamps']) - 20 }} timestamps...
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Amostra de mensagens -->
    @if(!empty($analysis['data']['messages']))
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-comments"></i> Amostra de Mensagens</h3>
        </div>
        <div class="card-body">
            <p class="text-muted">
                <i class="fas fa-info-circle"></i> 
                Mostrando as primeiras {{ count($analysis['data']['messages']) }} mensagens encontradas.
            </p>
            <div style="max-height: 400px; overflow-y: auto;">
                @foreach(array_slice($analysis['data']['messages'], 0, 10) as $index => $message)
                <div class="border-bottom mb-2 pb-2">
                    <small class="text-muted">#{{ $index + 1 }}</small>
                    <div class="mt-1">
                        {!! strip_tags($message, '<strong><em><span>') !!}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Informações da análise -->
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info"></i> Informações da Análise</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong><i class="fas fa-user"></i> Processado por:</strong> 
                    {{ Auth::user()->name }}
                </div>
                <div class="col-md-6">
                    <strong><i class="fas fa-clock"></i> Data do processamento:</strong> 
                    {{ $analysis['data']['processed_at']->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Botões de ação -->
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('whatsapp.analysis') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Nova Análise
            </a>
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimir Relatório
            </button>
        </div>
    </div>
@endsection

@section('css')
<style>
    @media print {
        .btn, .card-header, .navbar, .sidebar { display: none !important; }
        .card { border: 1px solid #000 !important; }
    }
</style>
@endsection 