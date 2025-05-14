{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@foreach($person->visitorMoviments as $visitor)
    <div class="card card-outline card-warning collapsed-card">
        <div class="card-header">
            <h3 class="card-title">{{$visitor->visitor->visitante_nome}}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="display: none;">
            <div class="card">
                <div class="card-footer">
                    @if($visitor->visitor->photos->count())
                        @foreach($visitor->visitor->photos as $photo)
                            <img class="profile-user-img img-fluid"
                                 src="data:image/jpeg;base64,{{ base64_encode(stream_get_contents($photo->visitantefotos_fotobin)) }}"
                                 alt="User profile picture">
                        @endforeach
                    @else
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{URL::asset('/images/iconepreso.png')}}"
                             alt="User profile picture">
                    @endif
                </div>
            </div>
            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                    <b>Parentesco</b> <span class="float-right">{{$visitor->parentesco_descricao}}</span>
                </li>
                <li class="list-group-item">
                    <b>Matrícula nº</b> <span class="float-right">{{$visitor->visitor->id_visitante}}</span>
                </li>
                <li class="list-group-item">
                    <b>Filiação</b> <span class="float-right">
                        {{$visitor->visitor->visitante_pai}} | {{$visitor->visitor->visitante_mae}}
                    </span>
                </li>
                <li class="list-group-item">
                    <b>Data de nascimento</b>
                    <span class="float-right">
                        {{\Illuminate\Support\Carbon::parse($visitor->visitor->visitante_datanascimento)->format('d/m/Y')}}
                    </span>
                </li>
                <li class="list-group-item">
                    <b>Telefone</b> <span class="float-right">{{$visitor->visitor->telefone_numero}}</span>
                </li>
                <li class="list-group-item">
                    <b>Logradouro</b> <span
                        class="float-right">{{$visitor->visitor->visitanteendereco_logradouro}}</span>
                </li>
                <li class="list-group-item">
                    <b>Município</b> <span class="float-right">{{$visitor->visitor->municipio_nome}}</span>
                </li>
                <li class="list-group-item">
                    <b>Observação</b> </br> <p class="float-right">{{$visitor->visitor->visitante_observacoes}}</p>
                </li>
            </ul>
        </div>
    </div>
@endforeach
