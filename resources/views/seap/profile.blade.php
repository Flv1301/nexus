{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card card-dark card-outline">
    <div class="card-body box-profile">
        <div class="text-center">
            @if($person->photos->count())
                <img class="profile-user-img img-fluid"
                     src="data:image/jpeg;base64,{{base64_encode(stream_get_contents($person->photos->first()->presofoto_fotobin))}}"
                     alt="Foto Preso"/>
            @else
                <img class="profile-user-img img-fluid img-circle"
                     src="{{URL::asset('/images/iconepreso.png')}}" alt="User profile picture"/>
            @endif
        </div>
        <h3 class="profile-username text-center">{{$person->preso_nome}}</h3>
        <p class="text-muted text-center">{{$person->presoalcunha_descricao}}</p>
        <p class="text-muted text-center">
            @if($person->situacaopreso_descricao == "EM MONITORACAO")
                <span>EM MONITORAÇÃO</span>
                @can('seap_monitoramento')
                    <a href="{{ route('seap.maps.monitor.stuck', ['id' => $person->id_preso]) }}"
                       target="_blank"
                       class="open-link-monitor ml-1"><i class="fas fa-lg fa-satellite" style="color: #218838"></i></a>
                @endcan
            @else
                {{$person->situacaopreso_descricao}}
            @endif
        </p>
        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item d-flex justify-content-between">
                <b>Infopen nº</b> <span class="text-info">{{$person->id_preso}}</span>
            </li>
            <li class="list-group-item">
                <b>Filiação</b></br><span class="text-info">{{$person->presofiliacao_pai}} </span>
                </br><span class="text-info">{{$person->presofiliacao_mae}}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <b>Nascimento</b> <span
                    class="text-info">@if($person->preso_datanascimento)
                        {{ \Illuminate\Support\Carbon::createFromFormat('d/m/Y H:i:s', $person->preso_datanascimento)->format('d/m/Y')}}
                    @endif</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <b>Telefone</b> <span class="text-info">{{$person->telefone_numero}}</span>
            </li>
            <br class="list-group-item">
            <b>Logradouro</b><span class="text-info">{{$person->enderecopreso_logradouro}}</span>
            </li>
            <li class="list-group-item  d-flex justify-content-between">
                <b>Bairro</b> <span class="text-info">{{$person->enderecopreso_bairro}}</span>
            </li>
            <li class="list-group-item  d-flex justify-content-between">
                <b>Município</b> <span class="text-info">{{$person->municipio_nome}}
                                    -{{$person->municipio_siglauf}}</span>
            </li>
        </ul>
    </div>
</div>
