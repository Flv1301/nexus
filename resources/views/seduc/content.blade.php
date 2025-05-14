{{--
 * author Herbety Thiago Maciel
 * version 1.0
 * since 05/05/2023
 * copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-header">Dados do Aluno</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>SIA</th>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Mãe</th>
                    <th>Pai</th>
                    <th>CPF</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$person['codigo_sia_unidade_ensino_origem']}}</td>
                    <td>{{$person['codigo_pessoa']}}</td>
                    <td>{{$person['nome_aluno']}}</td>
                    <td>{{$person['nome_mae']}}</td>
                    <td>{{$person['nome_pai']}}</td>
                    <td>{{$person['cpf']}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Dados de Matrícula</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Ano Letivo</th>
                    <th>Matrícula</th>
                    <th>Série</th>
                    <th>Período</th>
                    <th>Turno</th>
                    <th>Data</th>
                </tr>
                </thead>
                <tbody>
                @isset($person['dados_matricula'])
                    @foreach ($person['dados_matricula'] as $registration)
                        <tr>
                            <td>{{$registration['codigo_escola']}}</td>
                            <td>{{$registration['nome_escola']}}</td>
                            <td>{{$registration['ano_letivo']}}</td>
                            <td>{{$registration['codigo_matricula']}}</td>
                            <td>{{$registration['serie_matricula']}}</td>
                            <td>{{$registration['periodo_letivo']}}</td>
                            <td>{{$registration['turno_matricula']}}</td>
                            <td>{{\Illuminate\Support\Carbon::parse($registration['data_matricula'])->format('d/m/Y H:i')}}</td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Dados de Endereço</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Ano Letivo</th>
                    <th>Código</th>
                    <th>Logradouro</th>
                    <th>Bairro</th>
                    <th>Complemento</th>
                    <th>Cep</th>
                    <th>Município</th>
                    <th>Telefones</th>
                </tr>
                </thead>
                <tbody>
                @if($person['dados_endereco'])
                    @foreach($person['dados_endereco'] as $address)
                        <tr>
                            <td>{{$address['ano_letivo']}}</td>
                            <td>{{$address['codigo_pessoa']}}</td>
                            <td>{{$address['logradouro']}}</td>
                            <td>{{$address['bairro']}}</td>
                            <td>{{$address['complemento_endereco']}}</td>
                            <td>{{$address['cep']}}</td>
                            <td>{{$address['municipio']}}</td>
                            <td>{{$address['telefones']}}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
