{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 15/09/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-header"><span class="text-info text-lg">Dados</span></div>
    <div class="card-body">
        <table class="table table-striped">
            <tbody id="data">
            <tr>
                <td class="col-md-3"><strong>Número BOP:</strong></td>
                <td class="col-md-3" id="n_bop">{{$result->n_bop}}</td>
                <td class="col-md-3"><strong>Unidade Responsável:</strong></td>
                <td class="col-md-3">{{$result->unidade_responsavel}}</td>
            </tr>
            <tr>
                <td class="col-md-3"><strong>Registros:</strong></td>
                <td class="col-md-3">{{$result->registros}}</td>
                <td class="col-md-3"><strong>Data do Registro:</strong></td>
                <td class="col-md-3">{{$result->dt_registro}}</td>
            </tr>
            <tr>
                <td class="col-md-3"><strong>Data do Fato:</strong></td>
                <td class="col-md-3">{{$result->data_fato}}</td>
                <td class="col-md-3"><strong>Localização:</strong></td>
                <td class="col-md-3"><span>{{$result->latitude_fato}}</span>,
                    <span>{{$result->longitude_fato}}</span>
                </td>
            </tr>
            <tr>
                <td class="col-md-3"><strong>Natureza:</strong></td>
                <td class="col-md-3">{{$result->natureza}}</td>
                <td class="col-md-3"><strong>Meio Empregado:</strong></td>
                <td class="col-md-3">{{$result->meio_empregado}}</td>
            </tr>
            <tr>
                <td class="col-md-3"><strong>Localidade do Fato:</strong></td>
                <td class="col-md-3">{{$result->localidade_fato}}</td>
                <td class="col-md-3"><strong>Local da Ocorrência:</strong></td>
                <td class="col-md-3">{{$result->localgp_ocorrencia}}</td>
            </tr>
            <tr>
                <td class="col-md-3"><strong>Bairro do Fato:</strong></td>
                <td class="col-md-3">{{$result->ds_bairro_fato}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
@if(!$result->sigiloso || $result->can)
    <div class="card">
        <div class="card-header"><span class="text-info text-lg">Relato</span></div>
        <div class="card-body">
            <p>{{$result->boprel->relato}}</p>
        </div>
    </div>
    <div class="card col-md-12">
        <div class="card-header"><span class="text-info text-lg">Envolvidos</span></div>
        <div class="card-body">
            @foreach($result->bopenv->unique() as $env)
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
@endif


