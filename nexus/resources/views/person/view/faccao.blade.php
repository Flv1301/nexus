@can('sisfac')
<div class="card">
    <div class="card-header text-info">Dados da Facção</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                <strong>Status:</strong>
                <span class="badge {{ $person->active_orcrim ? 'badge-danger' : 'badge-success' }}">
                    {{ $person->active_orcrim ? 'ATIVO' : 'INATIVO' }}
                </span>
            </div>
            @if($person->orcrim)
            <div class="col-md-3">
                <strong>ORCRIM:</strong> {{ strtoupper($person->orcrim) }}
            </div>
            @endif
            @if($person->orcrim_office)
            <div class="col-md-3">
                <strong>Cargo:</strong> {{ strtoupper($person->orcrim_office) }}
            </div>
            @endif
            @if($person->orcrim_occupation_area)
            <div class="col-md-4">
                <strong>Área de Atuação:</strong> {{ strtoupper($person->orcrim_occupation_area) }}
            </div>
            @endif
        </div>
        @if($person->orcrim_matricula || $person->orcrim_padrinho || $person->vulgo_padrinho || $person->data_ingresso)
        <div class="row mt-3">
            @if($person->orcrim_matricula)
            <div class="col-md-3">
                <strong>Matrícula:</strong> {{ strtoupper($person->orcrim_matricula) }}
            </div>
            @endif
            @if($person->data_ingresso)
            <div class="col-md-3">
                <strong>Data de Ingresso:</strong> 
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
            </div>
            @endif
            @if($person->orcrim_padrinho)
            <div class="col-md-3">
                <strong>Padrinho:</strong> {{ strtoupper($person->orcrim_padrinho) }}
            </div>
            @endif
            @if($person->vulgo_padrinho)
            <div class="col-md-3">
                <strong>Vulgo Padrinho:</strong> {{ strtoupper($person->vulgo_padrinho) }}
            </div>
            @endif
        </div>
        @endif
        
        @if(!$person->orcrim && !$person->orcrim_office && !$person->orcrim_occupation_area && !$person->orcrim_matricula && !$person->orcrim_padrinho && !$person->vulgo_padrinho && !$person->data_ingresso)
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Nenhuma informação de facção cadastrada para esta pessoa.
        </div>
        @endif
    </div>
</div>
@else
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Acesso Restrito:</strong> Você não tem permissão para visualizar ou editar informações de facção.
</div>
@endcan 