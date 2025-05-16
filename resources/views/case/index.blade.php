@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('title','Lista de Casos')
<x-page-header title="Casos">
    <a href="{{route('case.create')}}" class="btn btn-success">Cadastrar</a>
</x-page-header>
@section('content')
    @include('sweetalert::alert')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Data',
                    'Número',
                    'Nome',
                    'Unidade',
                    'Setor',
                    'Usuário',
                    'Status',
                    ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                ];
                $config = [
                    'order' => [[1, 'desc']],
                    'columns' => [null, null, null, null, null, null, null, ['orderable' => false]],
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
            <x-adminlte-datatable id="tbl_cases" :heads="$heads" :config="$config" striped hoverable>
                @foreach($cases as $case)
                    <tr>
                        <td>{{$case->created_at->format('d/m/Y')}}</td>
                        <td>{{$case->identifier}}</td>
                        <td>{{$case->name}}</td>
                        <td>{{$case->unity->name}}</td>
                        <td>{{$case->sector->name}}</td>
                        <td>{{$case->user->nickname}}</td>
                        <td><span class="{{\App\Enums\StatusCaseEnum::from($case->status)->style()}}">
                                {{$case->status}}
                            </span>
                        </td>
                        <td>
                            @if($caseUserIds->contains($case->id) || ($case->sector_id == $user->sector_id && $user->coordinator))
                                <div class="d-flex">
                                    @can('caso.ler')
                                        <a href="{{route('case.analysis', $case)}}" class='btn btn-sm mx-1'
                                           title='Detalhe'>
                                            <i class='fa fa-lg fa-fw fa-eye'></i>
                                        </a>
                                    @endcan
                                    @can('caso.atualizar')
                                        @if(($case->user_id == $user->id || ($case->sector_id == $user->sector_id && $user->coordinator)) && $case->status !== 'CONCLUIDO')
                                            <a href="{{route('case.edit', $case)}}" class='btn btn-sm mx-1 text-primary'
                                               title='Edição'>
                                                <i class='fa fa-lg fa-fw fa-edit'></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('caso.excluir')
                                        @if($case->user_id == $user->id && $case->status !== 'CONCLUIDO' && !$case->procedures->count())
                                            <form class="d-inline" action="{{route('case.destroy', $case)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm text-danger mx-1 delete-alert"
                                                        title="Deletar">
                                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@endsection
