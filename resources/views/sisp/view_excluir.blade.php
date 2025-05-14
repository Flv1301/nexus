{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-header"><span class="text-info text-lg">Dados</span></div>
                    <div class="card-body">
                        @foreach($bops as $bop)
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td class="col-md-3"><strong>Número BOP:</strong></td>
                                    <td class="col-md-3">{{ $bop->n_bop }}</td>
                                    <td class="col-md-3"><strong>Unidade Responsável:</strong></td>
                                    <td class="col-md-3">{{ $bop->unidade_responsavel }}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><strong>Registros:</strong></td>
                                    <td class="col-md-3">{{ $bop->registros }}</td>
                                    <td class="col-md-3"><strong>Data do Registro:</strong></td>
                                    <td class="col-md-3">{{ date('d/m/Y H:i', strtotime($bop->dt_registro)) }}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><strong>Data do Fato:</strong></td>
                                    <td class="col-md-3">{{ $bop->data_fato }}</td>
                                    <td class="col-md-3"><strong>Latitude do Fato:</strong></td>
                                    <td class="col-md-3">{{ $bop->latitude_fato }}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><strong>Natureza:</strong></td>
                                    <td class="col-md-3">{{ $bop->natureza }}</td>
                                    <td class="col-md-3"><strong>Bairro do Fato:</strong></td>
                                    <td class="col-md-3">{{ $bop->ds_bairro_fato }}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><strong>Meio Empregado:</strong></td>
                                    <td class="col-md-3">{{ $bop->meio_empregado }}</td>
                                    <td class="col-md-3"><strong>Local da Ocorrência:</strong></td>
                                    <td class="col-md-3">{{ $bop->localgp_ocorrencia }}</td>
                                </tr>
                                <tr>
                                    <td class="col-md-3"><strong>Localidade do Fato:</strong></td>
                                    <td class="col-md-3">{{ $bop->localidade_fato }}</td>
                                    <td class="col-md-3"><strong>Longitude do Fato:</strong></td>
                                    <td class="col-md-3">{{ $bop->longitude_fato }}</td>
                                </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><span class="text-info text-lg">Relato</span></div>
                    <div class="card-body">
                        <p>
                            {{$bop->boprel->relato}}
                        </p>
                    </div>
                </div>
                <div class="card col-md-12">
                    <div class="card-header"><span class="text-info text-lg">Envolvidos</span></div>
                    <div class="card-body">
                        @foreach($bop->bopenv as $env)
                            <div class="row">
                                <div class="col-md-2">
                                    <span><strong>Atuação:</strong> {{$env->ds_atuacao}}</span>
                                </div>
                                <div class="col-md-3">
                                    <span><strong>Nome:</strong> {{$env->nm_envolvido}}</span>
                                </div>
                                <div class="col-md-2">
                                    <span><strong>CPF:</strong> {{$env->cpf}}</span>
                                </div>
                                <div class="col-md-2">
                                    <span><strong>Nascimento:</strong> {{$env->nascimento}}</span>
                                </div>
                                <div class="col-md-3">
                                    <span><strong>Mãe:</strong> {{$env->mae}}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
