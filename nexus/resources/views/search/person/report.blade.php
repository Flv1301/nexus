<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Pessoa Física - {{ $person->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/report.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .header img {
            max-height: 60px;
            margin-bottom: 5px;
        }
        
        .header h2 {
            margin: 5px 0;
            color: #333;
            font-size: 12px;
            font-weight: bold;
        }
        
        .section-title {
            background-color: #f0f0f0;
            padding: 6px;
            margin: 10px 0 0 0;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        
        .section-title-table {
            background-color: #f0f0f0;
            padding: 6px;
            margin: 8px 0 0 0;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        
        .section-content {
            padding: 8px;
            margin-bottom: 10px;
            background-color: #fafafa;
        }
        
        .identification-section {
            margin-bottom: 10px;
        }
        
        .person-info {
            display: flex;
            gap: 15px;
        }
        
        .person-details {
            flex: 1;
        }
        
        .person-photo {
            width: 100px;
            height: 120px;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            text-align: center;
            background-color: #f9f9f9;
        }
        
        .info-row {
            margin-bottom: 3px;
            font-size: 10px;
        }
        
        .info-label {
            font-weight: bold;
            font-size: 10px;
        }
        
        .info-value {
            font-size: 10px;
        }
        
        .two-column {
            display: flex;
            gap: 20px;
        }
        
        .column {
            flex: 1;
        }
        
        .data-section {
            margin-bottom: 8px;
        }
        
        .data-section h4 {
            background-color: #f0f0f0;
            padding: 6px;
            margin: 8px 0 0 0;
            font-size: 10px;
            font-weight: bold;
        }
        
        .data-section-content {
            padding: 8px;
            background-color: #fafafa;
        }
        
        .address-item, .contact-item {
            margin-bottom: 8px;
            padding: 6px;
            background-color: #f9f9f9;
            border-left: 3px solid #6c757d;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .address-item:nth-child(even), .contact-item:nth-child(even) {
            background-color: #ffffff;
        }
        
        .processo-item {
            margin-bottom: 8px;
            padding: 6px;
            background-color: #f9f9f9;
            border-left: 3px solid #007bff;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .processo-item:nth-child(even) {
            background-color: #ffffff;
        }
        
        .company-item {
            margin-bottom: 8px;
            padding: 6px;
            background-color: #f9f9f9;
            border-left: 3px solid #28a745;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .company-item:nth-child(even) {
            background-color: #ffffff;
        }
        
        .vehicle-item {
            margin-bottom: 8px;
            padding: 6px;
            background-color: #f9f9f9;
            border-left: 3px solid #ffc107;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .vehicle-item:nth-child(even) {
            background-color: #ffffff;
        }
        
        .visitante-item {
            margin-bottom: 8px;
            padding: 6px;
            background-color: #f9f9f9;
            border-left: 3px solid #dc3545;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .visitante-item:nth-child(even) {
            background-color: #ffffff;
        }
        
        .orcrim-item {
            margin-bottom: 8px;
            padding: 6px;
            background-color: #f9f9f9;
            border-left: 3px solid #6f42c1;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .orcrim-item:nth-child(even) {
            background-color: #ffffff;
        }
        
        .table-section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10px;
            margin-top: 0;
        }
        
        .table-section th,
        .table-section td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
            font-size: 10px;
            line-height: 1.2;
        }
        
        .table-section th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .table-section tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .section-title, .section-title-table {
                page-break-after: avoid;
            }
            
            .table-section {
                page-break-inside: avoid;
            }
            
            .data-section {
                page-break-inside: avoid;
            }
            
            /* Estilos para PDFs incorporados */
            .pdf-container {
                page-break-inside: avoid;
                margin: 10px 0;
            }
            
            object[type="application/pdf"] {
                page-break-inside: avoid;
                min-height: 800px;
            }
            
            .pdf-placeholder {
                page-break-inside: avoid;
                border: 2px solid #ccc;
                background-color: #f8f9fa;
                padding: 20px;
                text-align: center;
            }
            
            /* Estilos para imagens de PDF convertidas */
            .pdf-page-image {
                page-break-inside: avoid;
                max-width: 100%;
                width: auto;
                height: auto;
                border: 1px solid #ddd;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                margin: 10px auto;
                display: block;
                image-rendering: crisp-edges;
                image-rendering: -webkit-optimize-contrast;
            }
            
            .pdf-info-box {
                border: 2px solid #007bff;
                background-color: #f8f9fa;
                padding: 15px;
                margin: 10px 0;
                page-break-inside: avoid;
            }
            
            iframe {
                page-break-inside: avoid;
                max-width: 734px !important; /* Largura máxima para A4 com margens */
                width: 100% !important;
            }
            
            .pdf-render-container {
                page-break-inside: avoid;
                margin: 20px 0;
            }
            
            .pdf-loading {
                text-align: center;
                padding: 40px;
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 4px;
            }
            
            /* Controle específico para canvas de PDF */
            .pdf-page-canvas {
                display: block;
                margin: 10px auto;
                border: 1px solid #ddd;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                page-break-inside: avoid;
                max-width: 734px !important; /* Largura máxima para A4 */
                height: auto !important;
                width: auto !important;
            }
            
            /* Container das páginas PDF */
            .pdf-page-container {
                page-break-inside: avoid;
                margin: 15px 0;
                text-align: center;
            }
            
            /* Informações de redimensionamento */
            .pdf-resize-info {
                font-size: 7px !important;
                color: #888 !important;
                margin-bottom: 5px;
                font-style: italic;
            }
            
            .pdf-summary-info {
                margin-top: 10px !important;
                padding: 8px !important;
                background-color: #f8f9fa !important;
                border: 1px solid #dee2e6 !important;
                font-size: 8px !important;
                page-break-inside: avoid;
            }
            
            /* Esconde elementos desnecessários na impressão */
            .pdf-loading {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <img src="{{ asset('images/logo_mppa_transparente.png') }}" alt="Logo MPPA">
        <h2>FICHA DE PESSOA FÍSICA</h2>
    </div>

    <!-- Seção de Identificação -->
    <div class="identification-section">
        <div class="section-title">IDENTIFICAÇÃO</div>
        <div class="section-content">
            <div class="person-info">
                <div class="person-details">
                    @if($person->name)
                    <div class="info-row">
                        <span class="info-label">NOME:</span>
                        <span class="info-value">{{ strtoupper($person->name) }}</span>
                    </div>
                    @endif

                    @if($person->nickname)
                    <div class="info-row">
                        <span class="info-label">APELIDO:</span>
                        <span class="info-value">{{ strtoupper($person->nickname) }}</span>
                    </div>
                    @endif
                    
                    <div class="two-column">
                        <div class="column">
                            @if($person->cpf)
                            <div class="info-row">
                                <span class="info-label">CPF:</span>
                                <span class="info-value">{{ $person->cpf }}</span>
                            </div>
                            @endif
                            @if($person->birth_date)
                            <div class="info-row">
                                <span class="info-label">NASCIMENTO:</span>
                                <span class="info-value">
                                    @php
                                        try {
                                            if(is_string($person->birth_date) && strpos($person->birth_date, '/') !== false) {
                                                echo \Carbon\Carbon::createFromFormat('d/m/Y', $person->birth_date)->format('d/m/Y');
                                            } else {
                                                echo \Carbon\Carbon::parse($person->birth_date)->format('d/m/Y');
                                            }
                                        } catch(\Exception $e) {
                                            echo $person->birth_date;
                                        }
                                    @endphp
                                </span>
                            </div>
                            @endif
                            @if($person->voter_registration)
                            <div class="info-row">
                                <span class="info-label">TÍTULO ELEITOR:</span>
                                <span class="info-value">{{ $person->voter_registration }}</span>
                            </div>
                            @endif
                            @if($person->sex)
                            <div class="info-row">
                                <span class="info-label">SEXO:</span>
                                <span class="info-value">{{ strtoupper($person->sex) }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="column">
                            @if($person->birth_city || $person->uf_birth_city)
                            <div class="info-row">
                                <span class="info-label">NATURAL:</span>
                                <span class="info-value">{{ strtoupper($person->birth_city ?? '') }}{{ $person->uf_birth_city ? '-' . $person->uf_birth_city : '' }}</span>
                            </div>
                            @endif
                            @if($person->conselho_de_classe)
                            <div class="info-row">
                                <span class="info-label">CNH:</span>
                                <span class="info-value">{{ $person->conselho_de_classe }}</span>
                            </div>
                            @endif
                            @if($person->rg)
                            <div class="info-row">
                                <span class="info-label">RG:</span>
                                <span class="info-value">{{ $person->rg }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($person->mother || $person->father)
                    <div class="info-row">
                        <span class="info-label">FILIAÇÃO:</span>
                    </div>
                    @if($person->mother)
                    <div class="info-row">
                        <span class="info-label">MÃE:</span>
                        <span class="info-value">{{ strtoupper($person->mother) }}</span>
                    </div>
                    @endif
                    @if($person->father)
                    <div class="info-row">
                        <span class="info-label">PAI:</span>
                        <span class="info-value">{{ strtoupper($person->father) }}</span>
                    </div>
                    @endif
                    @endif

                    @if($person->spouse_name || $person->spouse_cpf)
                    <div class="info-row">
                        <span class="info-label">CÔNJUGE:</span>
                        <span class="info-value">
                            {{ strtoupper($person->spouse_name ?? '') }}
                            @if($person->spouse_cpf)
                                @if($person->spouse_name) - @endif
                                CPF: {{ $person->spouse_cpf }}
                            @endif
                        </span>
                    </div>
                    @endif

                    @if($person->occupation)
                    <div class="info-row">
                        <span class="info-label">OCUPAÇÃO:</span>
                        <span class="info-value">{{ strtoupper($person->occupation) }}</span>
                    </div>
                    @endif

                    @if($person->tatto)
                    <div class="info-row">
                        <span class="info-label">TATUAGEM:</span>
                        <span class="info-value">{{ strtoupper($person->tatto) }}</span>
                    </div>
                    @endif

                    @if($person->warrant !== null)
                    <div class="info-row">
                        <span class="info-label">MANDADO DE PRISÃO:</span>
                        <span class="info-value">{{ $person->warrant ? 'SIM' : 'NÃO' }}</span>
                    </div>
                    @endif

                    @if($person->dead !== null)
                    <div class="info-row">
                        <span class="info-label">ÓBITO:</span>
                        <span class="info-value">{{ $person->dead ? 'SIM' : 'NÃO' }}</span>
                    </div>
                    @endif

                    @if($person->observation)
                    <div class="info-row">
                        <span class="info-label">OBSERVAÇÃO:</span>
                        <span class="info-value">{!! $person->observation !!}</span>
                    </div>
                    @endif

                    @if($person->situacao)
                    <div class="info-row">
                        <span class="info-label">SITUAÇÃO:</span>
                        <span class="info-value">{{ strtoupper($person->situacao) }}</span>
                    </div>
                    @endif

                    @if($person->data_cautelar)
                    <div class="info-row">
                        <span class="info-label">DATA DA CAUTELAR:</span>
                        <span class="info-value">
                            @php
                                try {
                                    if(is_string($person->data_cautelar) && strpos($person->data_cautelar, '/') !== false) {
                                        echo \Carbon\Carbon::createFromFormat('d/m/Y', $person->data_cautelar)->format('d/m/Y');
                                    } else {
                                        echo \Carbon\Carbon::parse($person->data_cautelar)->format('d/m/Y');
                                    }
                                } catch(\Exception $e) {
                                    echo $person->data_cautelar;
                                }
                            @endphp
                        </span>
                    </div>
                    @endif

                    @if($person->data_denuncia)
                    <div class="info-row">
                        <span class="info-label">DATA DA DENÚNCIA:</span>
                        <span class="info-value">
                            @php
                                try {
                                    if(is_string($person->data_denuncia) && strpos($person->data_denuncia, '/') !== false) {
                                        echo \Carbon\Carbon::createFromFormat('d/m/Y', $person->data_denuncia)->format('d/m/Y');
                                    } else {
                                        echo \Carbon\Carbon::parse($person->data_denuncia)->format('d/m/Y');
                                    }
                                } catch(\Exception $e) {
                                    echo $person->data_denuncia;
                                }
                            @endphp
                        </span>
                    </div>
                    @endif

                    @if($person->data_condenacao)
                    <div class="info-row">
                        <span class="info-label">DATA DA CONDENAÇÃO:</span>
                        <span class="info-value">
                            @php
                                try {
                                    if(is_string($person->data_condenacao) && strpos($person->data_condenacao, '/') !== false) {
                                        echo \Carbon\Carbon::createFromFormat('d/m/Y', $person->data_condenacao)->format('d/m/Y');
                                    } else {
                                        echo \Carbon\Carbon::parse($person->data_condenacao)->format('d/m/Y');
                                    }
                                } catch(\Exception $e) {
                                    echo $person->data_condenacao;
                                }
                            @endphp
                        </span>
                    </div>
                    @endif
                </div>
                
                <div class="person-photo">
                    @if($person->images && $person->images->count() > 0)
                        @php
                            $image = $person->images->first();
                            if(\Illuminate\Support\Facades\Storage::exists($image->path)) {
                                $type = \Illuminate\Support\Facades\Storage::mimeType($image->path);
                                $content = \Illuminate\Support\Facades\Storage::get($image->path);
                                $content = base64_encode($content);
                            }
                        @endphp
                        @if(isset($content))
                            <img src="data:{{$type}};base64,{{$content}}" alt="Foto" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                        @else
                            SEM FOTO
                        @endif
                    @else
                        SEM FOTO
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Endereços -->
    @if($person->address && $person->address->count() > 0)
    <div class="data-section">
        <h4>ENDEREÇO</h4>
        <div class="data-section-content">
            @foreach($person->address as $index => $address)
            <div class="address-item">
                <strong>{{ $index + 1 }}.</strong>
                @if($address->cep) <strong>CEP:</strong> {{ $address->cep }}, @endif
                @if($address->address) <strong>ENDEREÇO:</strong> {{ strtoupper($address->address) }}, @endif
                @if($address->number) <strong>NÚMERO:</strong> {{ $address->number }}, @endif
                @if($address->district) <strong>BAIRRO:</strong> {{ strtoupper($address->district) }}, @endif
                @if($address->city) <strong>CIDADE:</strong> {{ strtoupper($address->city) }}, @endif
                @if($address->state) <strong>ESTADO:</strong> {{ strtoupper($address->state) }}, @endif
                @if($address->uf) <strong>UF:</strong> {{ strtoupper($address->uf) }}, @endif
                @if($address->complement) <strong>COMPLEMENTO:</strong> {{ strtoupper($address->complement) }}, @endif
                @if($address->reference_point) <strong>PONTO DE REFERÊNCIA:</strong> {{ strtoupper($address->reference_point) }}, @endif
                @if($address->observacao) <strong>OBSERVAÇÃO:</strong> {!! strtoupper($address->observacao) !!}, @endif
                @if($address->data_do_dado) 
                    <strong>DATA DO DADO:</strong> 
                    @php
                        try {
                            if(is_string($address->data_do_dado) && strpos($address->data_do_dado, '/') !== false) {
                                echo \Carbon\Carbon::createFromFormat('d/m/Y', $address->data_do_dado)->format('d/m/Y');
                            } else {
                                echo \Carbon\Carbon::parse($address->data_do_dado)->format('d/m/Y');
                            }
                        } catch(\Exception $e) {
                            echo $address->data_do_dado;
                        }
                    @endphp, 
                @endif
                @if($address->fonte_do_dado) <strong>FONTE DO DADO:</strong> {{ strtoupper($address->fonte_do_dado) }} @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Contatos -->
    @if(($person->telephones && $person->telephones->count() > 0) || ($person->emails && $person->emails->count() > 0))
    <div class="data-section">
        <h4>CONTATO</h4>
        <div class="data-section-content">
            @if($person->telephones && $person->telephones->count() > 0)
                @foreach($person->telephones as $index => $phone)
                <div class="contact-item">
                    <strong>{{ $index + 1 }}.</strong>
                    @if($phone->ddd && $phone->telephone) <strong>TELEFONE:</strong> ({{ $phone->ddd }}) {{ $phone->telephone }}, @endif
                    @if($phone->operator) <strong>OPERADORA:</strong> {{ strtoupper($phone->operator) }}, @endif
                    @if($phone->owner) <strong>TITULAR:</strong> {{ strtoupper($phone->owner) }}, @endif
                    @if($phone->status) <strong>STATUS:</strong> {{ strtoupper($phone->status) }}, @endif
                    @if($phone->data_do_dado) 
                        <strong>DATA DO DADO:</strong> 
                        @php
                            try {
                                if(is_string($phone->data_do_dado) && strpos($phone->data_do_dado, '/') !== false) {
                                    echo \Carbon\Carbon::createFromFormat('d/m/Y', $phone->data_do_dado)->format('d/m/Y');
                                } else {
                                    echo \Carbon\Carbon::parse($phone->data_do_dado)->format('d/m/Y');
                                }
                            } catch(\Exception $e) {
                                echo $phone->data_do_dado;
                            }
                        @endphp, 
                    @endif
                    @if($phone->fonte_do_dado) <strong>FONTE DO DADO:</strong> {{ strtoupper($phone->fonte_do_dado) }} @endif
                </div>
                @endforeach
            @endif
            
            @if($person->emails && $person->emails->count() > 0)
                @foreach($person->emails as $index => $email)
                <div class="contact-item">
                    <strong>{{ ($person->telephones ? $person->telephones->count() : 0) + $index + 1 }}. EMAIL:</strong> {{ $email->email }}
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @endif

    <!-- Redes Sociais -->
    @if($person->socials && $person->socials->count() > 0)
    <div class="data-section">
        <h4>REDES SOCIAIS</h4>
        <div class="data-section-content">
            @foreach($person->socials as $index => $social)
            <div class="contact-item">
                <strong>{{ $index + 1 }}.</strong>
                @if($social->type) <strong>REDE:</strong> {{ strtoupper($social->type) }}, @endif
                @if($social->social) <strong>ENDEREÇO/PERFIL:</strong> {{ $social->social }}, @endif
                @if($social->social_id) <strong>ID:</strong> {{ $social->social_id }}, @endif
                @if($social->vinculo) <strong>VÍNCULO:</strong> {{ strtoupper($social->vinculo) }} @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- INFOPEN - Dados Prisionais -->
    @if($person->stuck !== null || $person->evadido !== null || $person->detainee_registration || $person->detainee_date || $person->detainee_uf || $person->detainee_city || $person->cela || $person->situacao_infopen)
    <div class="data-section">
        <h4>INFOPEN - DADOS PRISIONAIS</h4>
        <div class="data-section-content">
            <div class="contact-item">
                @if($person->stuck !== null) <strong>PRESO:</strong> {{ $person->stuck ? 'SIM' : 'NÃO' }}, @endif
                @if($person->evadido !== null) <strong>EVADIDO:</strong> {{ $person->evadido ? 'SIM' : 'NÃO' }}, @endif
                @if($person->detainee_registration) <strong>MATRÍCULA:</strong> {{ $person->detainee_registration }}, @endif
                @if($person->detainee_date) 
                    <strong>DATA DA PRISÃO:</strong> 
                    @php
                        try {
                            if(is_string($person->detainee_date) && strpos($person->detainee_date, '/') !== false) {
                                echo \Carbon\Carbon::createFromFormat('d/m/Y', $person->detainee_date)->format('d/m/Y');
                            } else {
                                echo \Carbon\Carbon::parse($person->detainee_date)->format('d/m/Y');
                            }
                        } catch(\Exception $e) {
                            echo $person->detainee_date;
                        }
                    @endphp, 
                @endif
                @if($person->detainee_uf) <strong>UF PRISÃO:</strong> {{ $person->detainee_uf }}, @endif
                @if($person->detainee_city) <strong>CIDADE PRISÃO:</strong> {{ strtoupper($person->detainee_city) }}, @endif
                @if($person->cela) <strong>ESTABELECIMENTO/CELA:</strong> {{ strtoupper($person->cela) }}, @endif
                @if($person->situacao_infopen) <strong>SITUAÇÃO:</strong> {{ strtoupper($person->situacao_infopen) }} @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Visitantes -->
    @if($person->visitantes && $person->visitantes->count() > 0)
    <div class="data-section">
        <h4>VISITANTES</h4>
        <div class="data-section-content">
            @foreach($person->visitantes as $index => $visitante)
            <div class="visitante-item">
                <strong>{{ $index + 1 }}.</strong>
                @if($visitante->nome) <strong>NOME:</strong> {{ strtoupper($visitante->nome) }}, @endif
                @if($visitante->cpf) <strong>CPF:</strong> {{ $visitante->cpf }}, @endif
                @if($visitante->tipo_vinculo) <strong>TIPO DE VÍNCULO:</strong> {{ strtoupper($visitante->tipo_vinculo) }} @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- VCard -->
    @if($person->vcards && $person->vcards->count() > 0)
    <div class="data-section">
        <h4>VCARD - CONTATOS IMPORTADOS</h4>
        <div class="data-section-content">
            @foreach($person->vcards as $index => $vcard)
            <div class="contact-item">
                <strong>{{ $index + 1 }}.</strong>
                @if($vcard->name) <strong>NOME:</strong> {{ strtoupper($vcard->name) }}, @endif
                @if($vcard->phone) <strong>TELEFONE:</strong> {{ $vcard->phone }}, @endif
                @if($vcard->email) <strong>EMAIL:</strong> {{ $vcard->email }}, @endif
                @if($vcard->organization) <strong>ORGANIZAÇÃO:</strong> {{ strtoupper($vcard->organization) }} @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empresas -->
    @if($person->companies && $person->companies->count() > 0)
    <div class="data-section">
        <h4>EMPRESAS VINCULADAS</h4>
        <div class="data-section-content">
            @foreach($person->companies as $index => $company)
            <div class="company-item">
                <strong>{{ $index + 1 }}.</strong>
                @if($company->company_name) <strong>EMPRESA:</strong> {{ strtoupper($company->company_name) }}, @endif
                @if($company->fantasy_name) <strong>NOME FANTASIA:</strong> {{ strtoupper($company->fantasy_name) }}, @endif
                @if($company->cnpj) <strong>CNPJ:</strong> {{ $company->cnpj }}, @endif
                @if($company->phone) <strong>TELEFONE:</strong> {{ $company->phone }}, @endif
                @if($company->social_capital) <strong>CAPITAL SOCIAL:</strong> R$ {{ number_format($company->social_capital, 2, ',', '.') }}, @endif
                @if($company->status) <strong>SITUAÇÃO:</strong> {{ strtoupper($company->status) }}, @endif
                @if($company->cep) <strong>CEP:</strong> {{ $company->cep }}, @endif
                @if($company->address) <strong>ENDEREÇO:</strong> {{ strtoupper($company->address) }}, @endif
                @if($company->number) <strong>NÚMERO:</strong> {{ $company->number }}, @endif
                @if($company->district) <strong>BAIRRO:</strong> {{ strtoupper($company->district) }}, @endif
                @if($company->city) <strong>CIDADE:</strong> {{ strtoupper($company->city) }}, @endif
                @if($company->uf) <strong>UF:</strong> {{ strtoupper($company->uf) }}, @endif
                @if($company->cnae) <strong>CNAE:</strong> {{ $company->cnae }}, @endif
                @if($company->accountant) <strong>CONTADOR:</strong> {{ strtoupper($company->accountant) }} @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Veículos -->
    @if($person->vehicles && $person->vehicles->count() > 0)
    <div class="data-section">
        <h4>VEÍCULOS</h4>
        <div class="data-section-content">
            @foreach($person->vehicles as $index => $vehicle)
            <div class="vehicle-item">
                <strong>{{ $index + 1 }}.</strong>
                @if($vehicle->brand) <strong>MARCA:</strong> {{ strtoupper($vehicle->brand) }}, @endif
                @if($vehicle->model) <strong>MODELO:</strong> {{ strtoupper($vehicle->model) }}, @endif
                @if($vehicle->year) <strong>ANO:</strong> {{ $vehicle->year }}, @endif
                @if($vehicle->color) <strong>COR:</strong> {{ strtoupper($vehicle->color) }}, @endif
                @if($vehicle->plate) <strong>PLACA:</strong> {{ strtoupper($vehicle->plate) }}, @endif
                @if($vehicle->jurisdiction) <strong>JURISDIÇÃO:</strong> {{ strtoupper($vehicle->jurisdiction) }}, @endif
                @if($vehicle->status) <strong>SITUAÇÃO:</strong> {{ strtoupper($vehicle->status) }}, @endif
                @if($vehicle->renavam) <strong>RENAVAM:</strong> {{ $vehicle->renavam }}, @endif
                @if($vehicle->chassi) <strong>CHASSI:</strong> {{ strtoupper($vehicle->chassi) }} @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Vínculos ORCRIM -->
    @if($person->vinculoOrcrims && $person->vinculoOrcrims->count() > 0)
    <div class="section-title-table">VÍNCULOS ORCRIM</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>NOME</th>
                <th>ALCUNHA</th>
                <th>CPF</th>
                <th>TIPO VÍNCULO</th>
                <th>ORCRIM</th>
                <th>CARGO</th>
                <th>ÁREA ATUAÇÃO</th>
                <th>MATRÍCULA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->vinculoOrcrims as $vinculo)
            <tr>
                <td>{{ strtoupper($vinculo->name ?? '') }}</td>
                <td>{{ strtoupper($vinculo->alcunha ?? '') }}</td>
                <td>{{ $vinculo->cpf ?? '' }}</td>
                <td>{{ strtoupper($vinculo->tipo_vinculo ?? '') }}</td>
                <td>{{ strtoupper($vinculo->orcrim ?? '') }}</td>
                <td>{{ strtoupper($vinculo->cargo ?? '') }}</td>
                <td>{{ strtoupper($vinculo->area_atuacao ?? '') }}</td>
                <td>{{ strtoupper($vinculo->matricula ?? '') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Dados ORCRIM da própria pessoa -->
    @if($person->active_orcrim !== null || $person->orcrim || $person->orcrim_office || $person->orcrim_occupation_area || $person->orcrim_matricula || $person->orcrim_padrinho || $person->vulgo_padrinho || $person->data_ingresso)
    <div class="data-section">
        <h4>DADOS ORCRIM PESSOAIS</h4>
        <div class="data-section-content">
            <div class="orcrim-item">
                @if($person->active_orcrim !== null) <strong>ATIVO NA ORCRIM:</strong> {{ $person->active_orcrim ? 'SIM' : 'NÃO' }}, @endif
                @if($person->orcrim) <strong>ORCRIM:</strong> {{ strtoupper($person->orcrim) }}, @endif
                @if($person->orcrim_office) <strong>CARGO:</strong> {{ strtoupper($person->orcrim_office) }}, @endif
                @if($person->orcrim_occupation_area) <strong>ÁREA DE ATUAÇÃO:</strong> {{ strtoupper($person->orcrim_occupation_area) }}, @endif
                @if($person->orcrim_matricula) <strong>MATRÍCULA:</strong> {{ strtoupper($person->orcrim_matricula) }}, @endif
                @if($person->orcrim_padrinho) <strong>PADRINHO:</strong> {{ strtoupper($person->orcrim_padrinho) }}, @endif
                @if($person->vulgo_padrinho) <strong>VULGO PADRINHO:</strong> {{ strtoupper($person->vulgo_padrinho) }}, @endif
                @if($person->data_ingresso) 
                    <strong>DATA INGRESSO:</strong> 
                    @php
                        try {
                            if(is_string($person->data_ingresso) && strpos($person->data_ingresso, '/') !== false) {
                                echo \Carbon\Carbon::createFromFormat('d/m/Y', $person->data_ingresso)->format('d/m/Y');
                            } else {
                                echo \Carbon\Carbon::parse($person->data_ingresso)->format('d/m/Y');
                            }
                        } catch(\Exception $e) {
                            echo $person->data_ingresso;
                        }
                    @endphp 
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Antecedentes -->
    @if($person->pcpas && $person->pcpas->count() > 0)
    <div class="section-title-table">ANTECEDENTES</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>BO</th>
                <th>NATUREZA</th>
                <th>DATA</th>
                <th>UF</th>
                <th>CIDADE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->pcpas as $pcpa)
            <tr>
                <td>{{ $pcpa->bo ?? '' }}</td>
                <td>{{ strtoupper($pcpa->natureza ?? '') }}</td>
                <td>
                    @if($pcpa->data)
                        @php
                            try {
                                if(is_string($pcpa->data) && strpos($pcpa->data, '/') !== false) {
                                    echo \Carbon\Carbon::createFromFormat('d/m/Y', $pcpa->data)->format('d/m/Y');
                                } else {
                                    echo \Carbon\Carbon::parse($pcpa->data)->format('d/m/Y');
                                }
                            } catch(\Exception $e) {
                                echo $pcpa->data;
                            }
                        @endphp
                    @endif
                </td>
                <td>{{ $pcpa->uf ?? '' }}</td>
                <td>{{ strtoupper($pcpa->cidade ?? '') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- TJ -->
    @if($person->tjs && $person->tjs->count() > 0)
    <div class="data-section">
        <h4>PROCESSOS</h4>
        <div class="data-section-content">
            @foreach($person->tjs as $index => $tj)
            <div class="processo-item">
                <strong>{{ $index + 1 }}.</strong>
                @if($tj->processo) <strong>PROCESSO:</strong> {{ $tj->processo }}, @endif
                @if($tj->comarca) <strong>INSTÂNCIA:</strong> {{ strtoupper($tj->comarca) }}, @endif
                @if($tj->classe) <strong>CLASSE:</strong> {{ strtoupper($tj->classe) }}, @endif
                @if($tj->natureza) <strong>ASSUNTO:</strong> {{ strtoupper($tj->natureza) }}, @endif
                @if($tj->autor) <strong>AUTOR:</strong> {{ strtoupper($tj->autor) }}, @endif
                    @if($tj->data)
                    <strong>RECEBIDO EM:</strong>
                        @php
                            try {
                                if(is_string($tj->data) && strpos($tj->data, '/') !== false) {
                                    echo \Carbon\Carbon::createFromFormat('d/m/Y', $tj->data)->format('d/m/Y');
                                } else {
                                    echo \Carbon\Carbon::parse($tj->data)->format('d/m/Y');
                                }
                            } catch(\Exception $e) {
                                echo $tj->data;
                            }
                    @endphp, 
                    @endif
                @if($tj->uf) <strong>UF:</strong> {{ $tj->uf }}, @endif
                @if($tj->jurisdicao) <strong>JURISDIÇÃO:</strong> {{ strtoupper($tj->jurisdicao) }}, @endif
                @if($tj->processo_prevento) <strong>PROCESSO PREVENTO:</strong> {{ $tj->processo_prevento }}, @endif
                @if($tj->situacao_processo) <strong>SITUAÇÃO:</strong> {{ strtoupper($tj->situacao_processo) }}, @endif
                @if($tj->distribuicao) <strong>DISTRIBUIÇÃO:</strong> {{ strtoupper($tj->distribuicao) }}, @endif
                @if($tj->orgao_julgador) <strong>ÓRGÃO JULGADOR:</strong> {{ strtoupper($tj->orgao_julgador) }}, @endif
                @if($tj->orgao_julgador_colegiado) <strong>ÓRGÃO JULGADOR COLEGIADO:</strong> {{ strtoupper($tj->orgao_julgador_colegiado) }}, @endif
                @if($tj->competencia) <strong>COMPETÊNCIA:</strong> {{ strtoupper($tj->competencia) }}, @endif
                @if($tj->numero_inquerito_policial) <strong>Nº INQUÉRITO POLICIAL:</strong> {{ $tj->numero_inquerito_policial }}, @endif
                @if($tj->valor_causa) <strong>VALOR DA CAUSA:</strong> R$ {{ number_format($tj->valor_causa, 2, ',', '.') }}, @endif
                @if($tj->advogado) <strong>ADVOGADO:</strong> {{ strtoupper($tj->advogado) }}, @endif
                @if($tj->prioridade !== null) <strong>PRIORIDADE:</strong> {{ $tj->prioridade ? 'Sim' : 'Não' }}, @endif
                @if($tj->gratuidade !== null) <strong>GRATUIDADE:</strong> {{ $tj->gratuidade ? 'Sim' : 'Não' }} @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Armas -->
    @if($person->armas && $person->armas->count() > 0)
    <div class="section-title-table">ARMAS</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>CAC</th>
                <th>MARCA</th>
                <th>MODELO</th>
                <th>CALIBRE</th>
                <th>SINARM</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->armas as $arma)
            <tr>
                <td>{{ $arma->cac ?? '' }}</td>
                <td>{{ strtoupper($arma->marca ?? '') }}</td>
                <td>{{ strtoupper($arma->modelo ?? '') }}</td>
                <td>{{ strtoupper($arma->calibre ?? '') }}</td>
                <td>{{ $arma->sinarm ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Empregatício -->
    @if($person->rais && $person->rais->count() > 0)
                    <div class="section-title-table">Empregatício</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>EMPRESA/ÓRGÃO</th>
                <th>CNPJ</th>
                <th>TIPO VÍNCULO</th>
                <th>ADMISSÃO</th>
                <th>SITUAÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->rais as $rais)
            <tr>
                <td>{{ strtoupper($rais->empresa_orgao ?? '') }}</td>
                <td>{{ $rais->cnpj ?? '' }}</td>
                <td>{{ strtoupper($rais->tipo_vinculo ?? '') }}</td>
                <td>
                    @if($rais->admissao)
                        @php
                            try {
                                if(is_string($rais->admissao) && strpos($rais->admissao, '/') !== false) {
                                    echo \Carbon\Carbon::createFromFormat('d/m/Y', $rais->admissao)->format('d/m/Y');
                                } else {
                                    echo \Carbon\Carbon::parse($rais->admissao)->format('d/m/Y');
                                }
                            } catch(\Exception $e) {
                                echo $rais->admissao;
                            }
                        @endphp
                    @endif
                </td>
                <td>{{ strtoupper($rais->situacao ?? '') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- BNMP -->
    @if($person->bnmps && $person->bnmps->count() > 0)
    <div class="section-title-table">BNMP</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>N. MANDADO</th>
                <th>ÓRGÃO EXPEDIDOR</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->bnmps as $bnmp)
            <tr>
                <td>{{ $bnmp->numero_mandado ?? '' }}</td>
                <td>{{ strtoupper($bnmp->orgao_expedidor ?? '') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Bancário -->
    @if($person->bancarios && $person->bancarios->count() > 0)
    <div class="section-title-table">DADOS BANCÁRIOS</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>BANCO</th>
                <th>CONTA</th>
                <th>AGÊNCIA</th>
                <th>DATA CRIAÇÃO</th>
                <th>DATA EXCLUSÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->bancarios as $bancario)
            <tr>
                <td>{{ strtoupper($bancario->banco ?? '') }}</td>
                <td>{{ $bancario->conta ?? '' }}</td>
                <td>{{ $bancario->agencia ?? '' }}</td>
                <td>
                    @if($bancario->data_criacao)
                        @php
                            try {
                                if(is_string($bancario->data_criacao) && strpos($bancario->data_criacao, '/') !== false) {
                                    echo \Carbon\Carbon::createFromFormat('d/m/Y', $bancario->data_criacao)->format('d/m/Y');
                                } else {
                                    echo \Carbon\Carbon::parse($bancario->data_criacao)->format('d/m/Y');
                                }
                            } catch(\Exception $e) {
                                echo $bancario->data_criacao;
                            }
                        @endphp
                    @endif
                </td>
                <td>
                    @if($bancario->data_exclusao)
                        @php
                            try {
                                if(is_string($bancario->data_exclusao) && strpos($bancario->data_exclusao, '/') !== false) {
                                    echo \Carbon\Carbon::createFromFormat('d/m/Y', $bancario->data_exclusao)->format('d/m/Y');
                                }
                                else {
                                    echo \Carbon\Carbon::parse($bancario->data_exclusao)->format('d/m/Y');
                                }
                            } catch(\Exception $e) {
                                echo $bancario->data_exclusao;
                            }
                        @endphp
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Anexo - Documentos -->
    @if($person->docs && $person->docs->count() > 0)
    <div class="section-title-table">ANEXO</div>
    
    <div class="section-content">
        <p style="font-weight: bold; margin-bottom: 10px; font-size: 10px;">
            DOCUMENTOS ANEXADOS AO PROCESSO
        </p>
        <p style="margin-bottom: 15px; font-size: 10px;">
            Relacionamos abaixo os documentos que constam anexados ao presente relatório:
        </p>
    </div>
    
    <table class="table-section">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">ANEXO Nº</th>
                <th style="width: 37%;">NOME DO DOCUMENTO</th>
                <th style="width: 20%;">FONTE</th>
                <th style="width: 15%; text-align: center;">DATA</th>
                <th style="width: 20%; text-align: center;">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->docs as $index => $doc)
            <tr>
                <td style="text-align: center; font-weight: bold;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{!! strtoupper($doc->nome_doc ?? 'DOCUMENTO SEM NOME') !!}</td>
                <td>{{ strtoupper($doc->fonte ?? '') }}</td>
                <td style="text-align: center;">
                    @if($doc->data)
                        @php
                            try {
                                if(is_string($doc->data) && strpos($doc->data, '/') !== false) {
                                    echo \Carbon\Carbon::createFromFormat('d/m/Y', $doc->data)->format('d/m/Y');
                                } else {
                                    echo \Carbon\Carbon::parse($doc->data)->format('d/m/Y');
                                }
                            } catch(\Exception $e) {
                                echo $doc->data;
                            }
                        @endphp
                    @else
                        --/--/----
                    @endif
                </td>
                <td style="text-align: center; {{ $doc->upload ? 'color: green; font-weight: bold;' : 'color: red;' }}">
                    {{ $doc->upload ? '✓ ANEXADO' : '✗ SEM ARQUIVO' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 15px; font-size: 9px; color: #666;">
        <p><strong>Total de anexos:</strong> {{ $person->docs->count() }} documento(s)</p>
        <p><strong>Arquivos anexados:</strong> {{ $person->docs->where('upload', '!=', null)->count() }} documento(s)</p>
        <p><strong>Observação:</strong> Os documentos listados como "ANEXADO" constam fisicamente ou digitalmente no processo.</p>
    </div>

    <!-- PDFs dos Documentos -->
    @php
        $pdfDocs = $person->docs->filter(function($doc) {
            return $doc->upload && file_exists(public_path($doc->upload));
        });
    @endphp

    @if($pdfDocs->count() > 0)
        @foreach($pdfDocs as $index => $doc)
        <div class="page-break"></div>
        <div class="section-title-table">
            ANEXO {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }} - {{ strtoupper($doc->nome_doc ?? 'DOCUMENTO') }}
        </div>
        
        <div class="pdf-container">
            @php
                $filePath = public_path($doc->upload);
            @endphp
            
            @if(file_exists($filePath))
                <!-- Container para renderização do PDF via JavaScript -->
                <div id="pdf-container-{{ $index }}" class="pdf-render-container" data-pdf-url="{{ route('person.serve.document', ['personId' => $person->id, 'docId' => $doc->id]) }}">
                    <div class="pdf-loading">
                        <p style="text-align: center; padding: 40px; font-size: 12px;">
                            <i class="fas fa-spinner fa-spin"></i><br>
                            Carregando PDF: {{ strtoupper($doc->nome_doc ?? 'DOCUMENTO') }}...<br>
                            <small>Aguarde enquanto o documento é processado para impressão</small>
                        </p>
                    </div>
                </div>
                
                <!-- Fallback para quando JavaScript não está disponível -->
                <noscript>
                    <div class="pdf-info-box">
                        <p style="font-size: 11px; color: #666; margin: 0;">
                            <strong>📄 DOCUMENTO PDF</strong><br>
                            JavaScript necessário para melhor visualização.<br>
                            <em>Localização: {{ $doc->upload }}</em>
                        </p>
                    </div>
                    
                    <iframe src="{{ route('person.serve.document', ['personId' => $person->id, 'docId' => $doc->id]) }}" 
                            width="100%" 
                            height="800px" 
                            style="border: 1px solid #ccc;">
                        <div class="pdf-placeholder">
                            <p style="font-size: 12px; color: #666; margin: 0;">
                                <strong>ANEXO {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}:</strong> {{ strtoupper($doc->nome_doc ?? 'DOCUMENTO') }}<br>
                                <em>PDF não pode ser exibido. Consulte o arquivo original em: {{ $doc->upload }}</em>
                            </p>
                        </div>
                    </iframe>
                </noscript>
            @else
                <div class="pdf-placeholder">
                    <p style="font-size: 12px; color: #666; margin: 0;">
                        <strong>ANEXO {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}:</strong> {{ strtoupper($doc->nome_doc ?? 'DOCUMENTO') }}<br>
                        <em>Arquivo não encontrado ou não pode ser carregado.</em><br>
                        <small>Caminho esperado: {{ $doc->upload }}</small>
                    </p>
                </div>
            @endif
        </div>
        @endforeach
    @endif
    @endif

    <!-- Rodapé -->
    <div class="footer">
        <p>Relatório gerado em {{ date('d/m/Y H:i:s') }} por {{ auth()->user()->name ?? 'Sistema' }}</p>
    </div>

    <script>
        // Configurações para impressão A4 (em pixels com 96 DPI)
        const A4_WIDTH_PX = 794;  // ~210mm em 96 DPI
        const A4_HEIGHT_PX = 1123; // ~297mm em 96 DPI
        const PRINT_MARGIN = 60;   // Margem de segurança (15mm cada lado)
        const MAX_PDF_WIDTH = A4_WIDTH_PX - PRINT_MARGIN;
        const MAX_PDF_HEIGHT = A4_HEIGHT_PX - PRINT_MARGIN;

        window.onload = function() {
            // Carrega PDF.js se disponível ou tenta fallback
            if (typeof pdfjsLib === 'undefined') {
                loadPDFJS();
            } else {
                processPDFs();
            }
        }

        function loadPDFJS() {
            // Carrega PDF.js de CDN
            const script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
            script.onload = function() {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                processPDFs();
            };
            script.onerror = function() {
                // Se falhar ao carregar PDF.js, usa fallback
                useFallback();
            };
            document.head.appendChild(script);
        }

        function processPDFs() {
            const containers = document.querySelectorAll('.pdf-render-container');
            
            containers.forEach((container, index) => {
                const pdfUrl = container.getAttribute('data-pdf-url');
                if (pdfUrl) {
                    renderPDF(pdfUrl, container, index);
                }
            });
        }

        function renderPDF(url, container, index) {
            const loadingDiv = container.querySelector('.pdf-loading');
            
            // Configurar headers para autenticação
            const loadingTask = pdfjsLib.getDocument({
                url: url,
                httpHeaders: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/pdf'
                },
                withCredentials: true
            });
            
            loadingTask.promise.then(function(pdf) {
                loadingDiv.innerHTML = '<p style="text-align: center; padding: 20px; font-size: 12px;">Analisando e redimensionando ' + pdf.numPages + ' página(s)...</p>';
                
                const renderPromises = [];
                
                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                    renderPromises.push(renderPage(pdf, pageNum, container, index));
                }
                
                Promise.all(renderPromises).then(() => {
                    // Remove loading div após todas as páginas serem renderizadas
                    loadingDiv.remove();
                    
                    // Adiciona informações de redimensionamento se necessário
                    addResizeInfo(container, pdf.numPages);
                }).catch(error => {
                    console.error('Erro ao renderizar páginas:', error);
                    showError(container, 'Erro ao renderizar páginas do PDF');
                });
                
            }).catch(error => {
                console.error('Erro ao carregar PDF:', error);
                // Se falhar, tenta usar iframe como fallback
                showFallbackIframe(container, url);
            });
        }

        function showFallbackIframe(container, url) {
            container.innerHTML = `
                <div class="pdf-info-box" style="background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin-bottom: 10px;">
                    <p style="font-size: 11px; color: #856404; margin: 0;">
                        <strong>📄 DOCUMENTO PDF (MODO COMPATIBILIDADE)</strong><br>
                        Carregando via iframe devido a limitações de acesso. Se o documento não aparecer, 
                        pode ser necessário abrir em nova aba.
                    </p>
                </div>
                <iframe src="${url}" 
                        width="100%" 
                        height="800px" 
                        style="border: 1px solid #ccc; max-width: ${MAX_PDF_WIDTH}px;">
                    <div class="pdf-placeholder">
                        <p style="font-size: 12px; color: #666; margin: 20px;">
                            <strong>❌ Não foi possível carregar o documento.</strong><br>
                            <a href="${url}" target="_blank" style="color: #007bff;">Clique aqui para abrir em nova aba</a>
                        </p>
                    </div>
                </iframe>
            `;
        }

        function renderPage(pdf, pageNum, container, docIndex) {
            return pdf.getPage(pageNum).then(function(page) {
                // Se não é a primeira página, adiciona quebra de página
                if (pageNum > 1) {
                    const pageBreak = document.createElement('div');
                    pageBreak.className = 'page-break';
                    container.appendChild(pageBreak);
                    
                    const pageTitle = document.createElement('div');
                    pageTitle.className = 'section-title-table';
                    pageTitle.innerHTML = 'ANEXO ' + String(docIndex + 1).padStart(2, '0') + ' - PÁGINA ' + pageNum;
                    container.appendChild(pageTitle);
                }
                
                // Calcula a escala ideal para caber na página A4
                const originalViewport = page.getViewport({ scale: 1.0 });
                const optimalScale = calculateOptimalScale(originalViewport.width, originalViewport.height);
                
                // Usa escala otimizada
                const viewport = page.getViewport({ scale: optimalScale });
                
                const canvas = document.createElement('canvas');
                canvas.className = 'pdf-page-canvas';
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                
                // Define tamanho de exibição para garantir que caiba na página
                const displayWidth = Math.min(viewport.width, MAX_PDF_WIDTH);
                const displayHeight = Math.min(viewport.height, MAX_PDF_HEIGHT);
                
                canvas.style.width = displayWidth + 'px';
                canvas.style.height = displayHeight + 'px';
                canvas.style.maxWidth = '100%';
                canvas.style.height = 'auto';
                
                // Adiciona atributos para controle de impressão
                canvas.setAttribute('data-original-width', originalViewport.width);
                canvas.setAttribute('data-original-height', originalViewport.height);
                canvas.setAttribute('data-scale-used', optimalScale);
                
                const context = canvas.getContext('2d');
                
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                
                // Cria container para a página com informações de redimensionamento
                const pageContainer = document.createElement('div');
                pageContainer.className = 'pdf-page-container';
                pageContainer.style.marginBottom = '10px';
                pageContainer.style.textAlign = 'center';
                pageContainer.style.pageBreakInside = 'avoid';
                
                // Adiciona informação de redimensionamento se necessário
                if (optimalScale < 1.0) {
                    const resizeInfo = document.createElement('div');
                    resizeInfo.className = 'pdf-resize-info';
                    resizeInfo.style.fontSize = '8px';
                    resizeInfo.style.color = '#666';
                    resizeInfo.style.marginBottom = '5px';
                    resizeInfo.style.fontStyle = 'italic';
                    resizeInfo.innerHTML = `📏 Documento redimensionado para ${Math.round(optimalScale * 100)}% para ajustar à página A4`;
                    pageContainer.appendChild(resizeInfo);
                }
                
                pageContainer.appendChild(canvas);
                container.appendChild(pageContainer);
                
                return page.render(renderContext).promise;
            });
        }

        function calculateOptimalScale(originalWidth, originalHeight) {
            // Calcula as escalas necessárias para largura e altura
            const scaleByWidth = MAX_PDF_WIDTH / originalWidth;
            const scaleByHeight = MAX_PDF_HEIGHT / originalHeight;
            
            // Usa a menor escala para garantir que caiba em ambas as dimensões
            const optimalScale = Math.min(scaleByWidth, scaleByHeight, 2.0); // Máximo 2x para manter qualidade
            
            // Se o documento já é pequeno, usa escala mínima de 0.8 para manter legibilidade
            return Math.max(optimalScale, 0.5);
        }

        function addResizeInfo(container, numPages) {
            // Coleta informações sobre redimensionamento
            const canvases = container.querySelectorAll('canvas[data-scale-used]');
            const resizedPages = Array.from(canvases).filter(canvas => 
                parseFloat(canvas.getAttribute('data-scale-used')) < 1.0
            );
            
            if (resizedPages.length > 0) {
                const infoDiv = document.createElement('div');
                infoDiv.className = 'pdf-summary-info';
                infoDiv.style.marginTop = '15px';
                infoDiv.style.padding = '10px';
                infoDiv.style.backgroundColor = '#f8f9fa';
                infoDiv.style.border = '1px solid #dee2e6';
                infoDiv.style.borderRadius = '4px';
                infoDiv.style.fontSize = '9px';
                infoDiv.style.color = '#666';
                
                const avgScale = resizedPages.reduce((sum, canvas) => 
                    sum + parseFloat(canvas.getAttribute('data-scale-used')), 0
                ) / resizedPages.length;
                
                infoDiv.innerHTML = `
                    <p style="margin: 0; font-weight: bold;">ℹ️ Informações de Redimensionamento:</p>
                    <p style="margin: 5px 0 0 0;">
                        • ${resizedPages.length} de ${numPages} página(s) foram redimensionadas para caber na impressão<br>
                        • Escala média aplicada: ${Math.round(avgScale * 100)}%<br>
                        • Todas as páginas foram otimizadas para formato A4 com margens de segurança
                    </p>
                `;
                
                container.appendChild(infoDiv);
            }
        }

        function showError(container, message) {
            container.innerHTML = `
                <div class="pdf-placeholder">
                    <p style="font-size: 12px; color: #dc3545; margin: 0;">
                        <strong>❌ ERRO:</strong> ${message}<br>
                        <em>Utilizando visualização padrão do navegador como fallback</em>
                    </p>
                </div>
                <iframe src="${container.getAttribute('data-pdf-url')}" 
                        width="100%" 
                        height="600px" 
                        style="border: 1px solid #ccc; margin-top: 10px; max-width: ${MAX_PDF_WIDTH}px;">
                </iframe>
            `;
        }

        function useFallback() {
            // Se PDF.js não carregou, usa iframe como fallback com redimensionamento
            const containers = document.querySelectorAll('.pdf-render-container');
            containers.forEach(container => {
                const pdfUrl = container.getAttribute('data-pdf-url');
                container.innerHTML = `
                    <div class="pdf-info-box">
                        <p style="font-size: 11px; color: #666; margin: 0;">
                            <strong>📄 DOCUMENTO PDF (FALLBACK)</strong><br>
                            PDF.js não disponível, usando visualização padrão com limites de tamanho.<br>
                        </p>
                    </div>
                    <iframe src="${pdfUrl}" 
                            width="100%" 
                            height="800px" 
                            style="border: 1px solid #ccc; max-width: ${MAX_PDF_WIDTH}px;">
                    </iframe>
                `;
            });
        }
    </script>
</body>
</html> 