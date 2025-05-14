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
                <div class="card-header"><span class="text-info text-lg">Dados</span></div>
                <div class="row">
                    @foreach($bops as $bop)
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">BOP Key:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->bop_bop_key }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">SISP:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->sisp }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Número BOP:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->n_bop }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Unidade Responsável:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->unidade_responsavel }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Registros:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->registros }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Data do Registro:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ date('d/m/Y H:i', strtotime($bop->dt_registro)) }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Data do Fato:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->data_fato }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Natureza:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->natureza }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Bairro do Fato:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->ds_bairro_fato }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Meio Empregado:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->meio_empregado }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Local da Ocorrência:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->localgp_ocorrencia }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Localidade do Fato:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->localidade_fato }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Latitude do Fato:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->latitude_fato }}</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Longitude do Fato:</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">{{ $bop->longitude_fato }}</p>
                                </div>
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
</div>
