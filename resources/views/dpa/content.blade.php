{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}

<div class="col-md-12">
    <div class="row">
        <div class="col-md-2"><strong>Data Cadastro:</strong></div>
        <div class="col-md-6">{{ \Illuminate\Support\Carbon::parse($person->dt_cadastro)->format('d/m/Y') }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Nome:</strong></div>
        <div class="col-md-6">{{ $person->nm_proprietario }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>CPF:</strong></div>
        <div class="col-md-6">{{ $person->cpf_propr }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Registro Geral:</strong></div>
        <div class="col-md-6">{{ $person->rg_propr }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Telefone:</strong></div>
        <div class="col-md-6">{{ $person->propr_telefone }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>E-Mail:</strong></div>
        <div class="col-md-6">{{ $person->propr_email }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Endereço:</strong></div>
        <div
            class="col-md-10">{{$person->end_propr}}, {{$person->desc_bairro_proprietario}}
            , {{$person->desc_municipio_proprietario}}, {{$person->end_propr_comp}}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>CNPJ:</strong></div>
        <div class="col-md-6">{{ $person->cnpj_prop }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Razão Social:</strong></div>
        <div class="col-md-6">{{ $person->razao_social }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Responsável:</strong></div>
        <div class="col-md-6">{{ $person->responsavel }}</div>
    </div>
    <div class="row">
        <div class="col-md-2"><strong>Telefone:</strong></div>
        <div class="col-md-6">{{ $person->responsavel }}</div>
    </div>
</div>

