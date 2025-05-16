<div class="card">
    <div class="card-body">
        @php
            $heads = [
                'Solicitante',
                'Solicitado',
                'Data',
                'Data Limite',
                'Status',
                ['label' => 'Opções', 'no-export' => true],
            ];
            $config = [
                'order' => [[0, 'asc']],
                'columns' => [null, null, null, null, null, ['orderable' => false]],
                'language' => [
                        'paginate' => [
                            'first' => 'Primeiro',
                            'last' => 'Último',
                            'next' => 'Próximo',
                            'previous' => 'Anterior',
                        ],
                        'search' => 'Pesquisar na Tabela',
                        'lengthMenu'=>    "Mostrar  _MENU_  Resultados",
                        'info'=>           "Mostrando _START_ a _END_ de _TOTAL_ Resultados.",
                        'infoEmpty'=>      "Mostrando 0 Resultados.",
                        'infoFiltered'=>   "(Filtro de _MAX_ Resultados no total)",
                        'loadingRecords'=> "Pesquisando...",
                        'zeroRecords'=>    "Nem um dado(s) encontrado(s)",
                        'emptyTable'=>     "Sem dados!",
                    ],
            ];
        @endphp
        <x-adminlte-datatable id="proceduresview" :heads="$heads" :config="$config" striped>
            @foreach($procedures as $procedure)
                <tr>
                    <td>
                        {{$procedure->requestUnity->name}}
                        <i class="fas fa-arrow-right m-1"></i>
                        {{$procedure->requestSector->name}}
                        <i class="fas fa-arrow-right m-1"></i>
                        {{$procedure->requestUser->name}}
                    </td>
                    <td>
                        {{$procedure->unity->name}}
                        <i class="fas fa-arrow-right m-1"></i>
                        {{$procedure->sector->name}}
                        <i class="fas fa-arrow-right m-1"></i>
                        {{$procedure->user->nickname}}
                    </td>
                    <td class="col-md-1">{{$procedure->created_at->format('d/m/Y')}}</td>
                    <td class="col-md-1">{{$procedure->limit_date}}</td>
                    <td class="col-md-1">
                          <span class="{{\App\Enums\StatusCaseEnum::from($procedure->status)->style()}}">
                                {{$procedure->status}}
                          </span>
                    </td>
                    @php $response = $procedure->responses->count() @endphp
                    <td class="col-md-1">
                        <div class="d-flex">
                            <a onclick='modalProcedure(this)' class='btn btn-sm' title='Visualizar Solicitação'
                               data-toggle='modal' data-target='#modalProcedureView'
                               data-url="{{route('procedure.find',['id' => $procedure->id])}}">
                                <i class='fa fa-lg fa-fw fa-eye'></i>
                            </a>
                            @if($procedure->user_id === $user->id && $procedure->status != 'CONCLUIDO')
                                <a class='btn btn-sm' title='Responder'
                                   data-toggle='modal'
                                   data-target='#modalProcedureResponse'
                                   onclick='modalProcedureResponse(this)'
                                   data-id="{{$procedure->id}}">
                                    <i class='fa fa-lg fa-fw fa-share text-secondary'></i>
                                </a>
                            @endif
                            @if($response)
                                <a href="{{route('procedure.responses', $procedure)}}" class='btn btn-sm' title='Visualizar Respostas'>
                                    <i class='fa fa-lg fa-fw fa-check-circle text-success'></i>
                                </a>
                            @endif
                            @if(!$response && $procedure->request_user_id === $user->id)
                                <form action="{{route('procedure.destroy', $procedure)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm text-danger mx-1 delete-alert" title="Deletar">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
@include('case.procedure.view')
