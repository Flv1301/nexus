{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"><strong>Prontuário:</strong></div>
        <div class="col-md-6">{{ $person->prontuario }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Guia:</strong></div>
        <div class="col-md-6">{{ $person->guia }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Data Reg. Criminal:</strong></div>
        <div class="col-md-6">{{ $person->data_regcriminal ?? '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Nome:</strong></div>
        <div class="col-md-6">{{ $person->nome }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Pai:</strong></div>
        <div class="col-md-6">{{ $person->pai }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Mãe:</strong></div>
        <div class="col-md-6">{{ $person->mae }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Data Nascimento:</strong></div>
        <div
            class="col-md-6">{{ \Carbon\Carbon::parse($person->data_nascimento)->format('d/m/Y') }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Sexo:</strong></div>
        <div class="col-md-6">{{ $person->sexo }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>CPF:</strong></div>
        <div class="col-md-6">{{ trim($person->cpf) ?: '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>RG:</strong></div>
        <div class="col-md-6">{{ $person->rg }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>UF RG:</strong></div>
        <div class="col-md-2">{{ $person->uf_rg }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Data Exped. RG:</strong></div>
        <div class="col-md-6">{{ $person->data_exped_rg ?? '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Tipo Doc:</strong></div>
        <div class="col-md-6">{{ $person->tipo_doc ?? '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Num. Doc:</strong></div>
        <div class="col-md-6">{{ $person->num_doc ?? '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Alcunha:</strong></div>
        <div class="col-md-6">{{ $person->alcunha ?? '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Local Detenção:</strong></div>
        <div class="col-md-6">{{ $person->local_detencao }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Profissão:</strong></div>
        <div class="col-md-6">{{ $person->profissao ?? '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Escolaridade:</strong></div>
        <div class="col-md-6">{{ $person->escolaridade ?? '-' }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Nacionalidade:</strong></div>
        <div class="col-md-6">{{ $person->nacionalidade }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Estado Civil:</strong></div>
        <div class="col-md-6">{{ $person->estado_civil }}</div>
    </div>
</div>
