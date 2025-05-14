{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="row ">
    <div class="col-md-3">
        <div class="card card-dark card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                         src="{{URL::asset('/images/image-office_low.png')}}" alt="User profile picture">
                </div>
                <h3 class="profile-username text-center">{{$person->nome_servidor}}</h3>
                <p class="text-muted text-center">{{$person->cargo}}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <b>CPF</b> <span class="text-info">{{$person->cpf}}</span>
                    </li>
                    <li class="list-group-item  d-flex justify-content-between">
                        <b>RG</b> <span class="text-info">{{$person->rg}} </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <b>Matricula</b> <span class="text-info">{{$person->matricula}} </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <b>Data Nasc.</b> <span class="text-info">
                                    {{\Illuminate\Support\Carbon::createFromFormat('Y-m-d',$person->nascimento)->format('d/m/Y')}} </span>
                    </li>
                    <li class="list-group-item">
                        <b>Escolaridade</b> </br> <span class="text-info">{{$person->escolaridade}}  </span>
                    </li>
                    <li class="list-group-item">
                        <b>lotação</b> </br> <span class="text-info">{{$person->lotacao}}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <b>situação</b> <span class="text-info">{{$person->situacao}} </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <b>Vínculo</b> <span class="text-info">{{$person->vinculo}} </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="active nav-link" href="#hist" data-toggle="tab">Histórico de
                            Lotação</a></li>
                    <li class="nav-item"><a class="nav-link" href="#courses" data-toggle="tab">Cursos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#averb" data-toggle="tab">Averbações</a></li>
                    <li class="nav-item"><a class="nav-link" href="#timejob" data-toggle="tab">Tempo de Serviço</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class=" tab-pane" id="courses">
                        <div class="timeline">
                            @foreach($person->courses as $course)
                                <div>
                                    <i class="fas fa-book bg-blue"></i>
                                    <div class="timeline-item">
                                            <span class="time"><i
                                                    class="fas fa-clock"></i> {{\Illuminate\Support\Carbon::parse($course->dt_cadastro)->format('d/m/Y')}}</span>
                                        <h3 class="timeline-header"><a href="#">{{$course->nom_curso}} </a></h3>
                                        <div class="timeline-body">
                                            {{$course->nom_curso}}
                                            <br>
                                            {{$course->nom_instituicao}}
                                            <br>
                                            Conclusão: {{$course->ano_conclusao}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane active" id="hist">
                        <div class="timeline">
                            @foreach($person->locationHistory as $move)
                                <div>
                                    <i class="fas fa-arrow-down bg-blue"></i>
                                    <div class="timeline-item">
                                                <span class="time"><i class="fas fa-clock"></i>
                                                    {{\Carbon\Carbon::parse($move->dat_lotacao)->format('d/m/Y')}}
                                                </span>
                                        <h3 class="timeline-header"><a href="#">{{$move->lotacao}}
                                                - {{$move->lotacao_pai}}</a></h3>
                                        <div class="timeline-body">
                                            {{$move->num_portaria}}
                                            <br>
                                            {{$move->ordem_servico}}
                                            <br>
                                            {{$move->motivo}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class=" tab-pane" id="averb">
                        <div class="timeline">
                            @foreach($person->annotations as $averb)
                                <div>
                                    <i class="fas fa-briefcase bg-blue"></i>
                                    <div class="timeline-item">
                                                <span class="time"><i class="fas fa-clock"></i>
                                                    {{\Illuminate\Support\Carbon::parse($averb->dt_inicio_trabalho)->format('d/m/Y')}}
                                                    - {{\Illuminate\Support\Carbon::parse($averb->dt_fim_trabalho)->format('d/m/Y')}}
                                                </span>
                                        <h3 class="timeline-header"><a href="#">{{$averb->local_trabalho}} </a>
                                        </h3>
                                        <div class="timeline-body">
                                            {{$averb->observacao}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class=" tab-pane" id="timejob">
                        <div class="timeline">
                            @foreach($person->serviceTime as $time)
                                <div>
                                    <i class="fas fa-briefcase bg-blue"></i>
                                    <div class="timeline-item">
                                            <span class="time"><i
                                                    class="fas fa-clock"></i> {{\Illuminate\Support\Carbon::parse($time->dt_declaracao)->format('d/m/Y')}}</span>
                                        <h3 class="timeline-header"><a href="#">{{$time->tempo_servico}} </a>
                                        </h3>
                                        <div class="timeline-body">
                                            Dias trabalhados {{$time->dias_trabalhados}} <br>
                                            Dias Averbados {{$time->dias_averbados}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
