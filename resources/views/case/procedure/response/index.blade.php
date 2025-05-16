@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title',"Respostas")
<x-page-header title="Respostas de Tramitação">
    <div>
        <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
           type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
    </div>
</x-page-header>
@section('content')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Analista',
                    'Data',
                    'Data Limite',
                    'Status',
                    ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                ];
                $config = [
                    'order' => [[0, 'asc']],
                    'columns' => [null, null, null, null, ['orderable' => false]],
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
            <x-adminlte-datatable
                id="proceduresview"
                :heads="$heads"
                :config="$config"
                striped hoverable>
                @foreach($procedure->responses as $response)
                    <tr>
                        <td>{{$response->procedure->user->nickname}}</td>
                        <td>{{$response->created_at->format('d/m/Y')}}</td>
                        <td>{{$response->procedure->limit_date->format('d/m/Y')}}</td>
                        <td>
                          <span class="{{\App\Enums\StatusCaseEnum::from($response->status)->style()}}">
                            {{$response->status}}
                          </span>
                        </td>
                        <td>
                            <a onclick='modalProcedure(this)' class='btn btn-sm mx-1' title='Detalhe'
                               data-toggle='modal' data-target='#modalResponseView'
                               data-url="{{route('procedure.response.view', ['id' => $response])}}">
                                <i class='fa fa-lg fa-fw fa-eye'></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
    @include('case.procedure.response.view')
@endsection
