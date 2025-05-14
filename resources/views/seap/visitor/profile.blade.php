{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card card-dark card-outline">
    <div class="card-body box-profile">
        <div class="text-center mb-2">
            @if($person->photos->count())
                <img class="img-thumbnail" width="200px" height="200px"
                     src="data:image/jpeg;base64,{{base64_encode(stream_get_contents($person->photos->first()->visitantefotos_fotobin))}}"
                     alt="Foto Do Visitante"/>
            @endif
            <h3 class="profile-username">{{$person->visitante_nome}}</h3>
            <span
                class="text-info">Data de Cadastro: {{\Illuminate\Support\Carbon::parse($person->visitante_datacadastro)->format('d/m/Y')}}</span>
        </div>
        <div class="row">
            <div class="col-md-6">
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Filiação</b><br>
                        Pai: <span class="text-info">{{$person->visitante_pai}}</span><br>
                        Mãe: <span class="text-info">{{$person->visitante_mae}}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Data de Nascimento:</b> <span
                            class="text-info">{{\Illuminate\Support\Carbon::parse($person->visitante_datanascimento)->format('d/m/Y')}}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Sexo:</b> <span
                            class="text-info">{{$person->visitante_sexo == 'F' ? 'Feminino' : 'Masculino'}}</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Profissão:</b> <span class="text-info">{{$person->grauinstrucao_descricao}}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Logradouro:</b> <span class="text-info">{{$person->visitanteendereco_logradouro}}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Município:</b> <span class="text-info">{{$person->municipio_nome}}</span>
                    </li>
                    <li class="list-group-item">
                        <b>Telefone:</b> <span class="text-info">{{$person->telefone_numero}}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div>
            <b class="m-2">Observações:</b>
            <p>{{$person->visitante_observacoes}}</p>
        </div>
    </div>
</div>

