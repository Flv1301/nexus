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
            padding: 8px;
            margin: 15px 0 0 0;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        
        .section-title-table {
            background-color: #f0f0f0;
            padding: 8px;
            margin: 15px 0 0 0;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        
        .section-content {
            padding: 10px;
            margin-bottom: 15px;
            background-color: #fafafa;
        }
        
        .identification-section {
            margin-bottom: 15px;
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
            margin-bottom: 4px;
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
            margin-bottom: 15px;
        }
        
        .data-section h4 {
            background-color: #f0f0f0;
            padding: 8px;
            margin: 15px 0 0 0;
            font-size: 10px;
            font-weight: bold;
        }
        
        .data-section-content {
            padding: 10px;
            background-color: #fafafa;
        }
        
        .address-item, .contact-item {
            margin-bottom: 6px;
            font-size: 10px;
            line-height: 1.3;
        }
        
        .table-section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
            margin-top: 0;
        }
        
        .table-section th,
        .table-section td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 10px;
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

                    @if($person->observation)
                    <div class="info-row">
                        <span class="info-label">OBSERVAÇÃO:</span>
                        <span class="info-value">{{ $person->observation }}</span>
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
                @if($address->complement) <strong>COMPLEMENTO:</strong> {{ strtoupper($address->complement) }} @endif
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
                    @if($phone->status) <strong>STATUS:</strong> {{ strtoupper($phone->status) }} @endif
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
                @if($social->social) <strong>ENDEREÇO/PERFIL:</strong> {{ $social->social }} @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- INFOPEN - Dados Prisionais -->
    @if($person->stuck !== null || $person->evadido !== null || $person->detainee_registration || $person->detainee_date || $person->detainee_uf || $person->detainee_city || $person->cela)
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
                @if($person->cela) <strong>ESTABELECIMENTO/CELA:</strong> {{ strtoupper($person->cela) }} @endif
            </div>
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
    <div class="page-break"></div>
    <div class="section-title-table">EMPRESAS VINCULADAS</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>EMPRESA</th>
                <th>CNPJ</th>
                <th>ENDEREÇO</th>
                <th>SITUAÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->companies as $company)
            <tr>
                <td>{{ strtoupper($company->company_name ?? '') }}</td>
                <td>{{ $company->cnpj ?? '' }}</td>
                <td>
                    {{ strtoupper($company->address ?? '') }}
                    @if($company->district), {{ strtoupper($company->district) }}@endif
                    @if($company->city), {{ strtoupper($company->city) }}@endif
                </td>
                <td>{{ strtoupper($company->status ?? '') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Veículos -->
    @if($person->vehicles && $person->vehicles->count() > 0)
    <div class="section-title-table">VEÍCULOS</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>MARCA</th>
                <th>MODELO</th>
                <th>ANO</th>
                <th>COR</th>
                <th>PLACA</th>
                <th>JURISDIÇÃO</th>
                <th>SITUAÇÃO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->vehicles as $vehicle)
            <tr>
                <td>{{ strtoupper($vehicle->brand ?? '') }}</td>
                <td>{{ strtoupper($vehicle->model ?? '') }}</td>
                <td>{{ $vehicle->year ?? '' }}</td>
                <td>{{ strtoupper($vehicle->color ?? '') }}</td>
                <td>{{ strtoupper($vehicle->plate ?? '') }}</td>
                <td>{{ strtoupper($vehicle->jurisdiction ?? '') }}</td>
                <td>{{ strtoupper($vehicle->status ?? '') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
    @if($person->orcrim || $person->orcrim_office || $person->orcrim_occupation_area || $person->orcrim_matricula || $person->orcrim_padrinho)
    <div class="section-title-table">DADOS ORCRIM PESSOAIS</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>ORCRIM</th>
                <th>CARGO</th>
                <th>ÁREA DE ATUAÇÃO</th>
                <th>MATRÍCULA</th>
                <th>PADRINHO</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ strtoupper($person->orcrim ?? '') }}</td>
                <td>{{ strtoupper($person->orcrim_office ?? '') }}</td>
                <td>{{ strtoupper($person->orcrim_occupation_area ?? '') }}</td>
                <td>{{ strtoupper($person->orcrim_matricula ?? '') }}</td>
                <td>{{ strtoupper($person->orcrim_padrinho ?? '') }}</td>
            </tr>
        </tbody>
    </table>
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
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- TJ -->
    @if($person->tjs && $person->tjs->count() > 0)
    <div class="section-title-table">TRIBUNAL DE JUSTIÇA</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>PROCESSO</th>
                <th>NATUREZA</th>
                <th>DATA</th>
                <th>UF</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->tjs as $tj)
            <tr>
                <td>{{ $tj->processo ?? '' }}</td>
                <td>{{ strtoupper($tj->natureza ?? '') }}</td>
                <td>
                    @if($tj->data)
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
                        @endphp
                    @endif
                </td>
                <td>{{ $tj->uf ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
            </tr>
        </thead>
        <tbody>
            @foreach($person->armas as $arma)
            <tr>
                <td>{{ $arma->cac ?? '' }}</td>
                <td>{{ strtoupper($arma->marca ?? '') }}</td>
                <td>{{ strtoupper($arma->modelo ?? '') }}</td>
                <td>{{ strtoupper($arma->calibre ?? '') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- RAIS -->
    @if($person->rais && $person->rais->count() > 0)
    <div class="section-title-table">RAIS</div>
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

    <!-- Documentos -->
    @if($person->docs && $person->docs->count() > 0)
    <div class="section-title-table">DOCUMENTOS ANEXADOS</div>
    <table class="table-section">
        <thead>
            <tr>
                <th>NOME DO DOCUMENTO</th>
                <th>DATA</th>
                <th>ARQUIVO</th>
            </tr>
        </thead>
        <tbody>
            @foreach($person->docs as $doc)
            <tr>
                <td>{{ strtoupper($doc->nome_doc ?? '') }}</td>
                <td>
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
                    @endif
                </td>
                <td>{{ $doc->upload ? 'ANEXADO' : 'SEM ARQUIVO' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Rodapé -->
    <div class="footer">
        <p>Relatório gerado em {{ date('d/m/Y H:i:s') }} por {{ auth()->user()->name ?? 'Sistema' }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html> 