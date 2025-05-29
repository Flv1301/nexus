@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('title','Lista de Casos')
<x-page-header title="Movimento">
    <a href="{{route('case.create')}}" class="btn btn-success">Cadastrar</a>
</x-page-header>
@section('content')
    @include('sweetalert::alert')
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Data',
                    'PJE',
                    'Pic',
                    'Processo',
                    'SAJ',
                    'Portaria',
                    'Nome do Alvo',
                    'Status',
                    'Prazo',
                    ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                ];
                $config = [
                    'order' => [[1, 'desc']],
                    'columns' => [null, null, null, null, null, null, null, null, null, ['orderable' => false]],
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
                        <td>
                            @if($case->date)
                                @php
                                    $rawDate = $case->getRawOriginal('date');
                                    $formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $rawDate)->format('d/m/Y');
                                @endphp
                                {{ $formattedDate }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{$case->name}}</td>
                        <td>{{$case->process ?? 'N/A'}}</td>
                        <td>{{$case->secondary_process ?? 'N/A'}}</td>
                        <td>{{$case->saj ?? 'N/A'}}</td>
                        <td>{{$case->portaria ?? 'N/A'}}</td>
                        <td>
                            @if($case->persons && $case->persons->count() > 0)
                                @foreach($case->persons as $person)
                                    {{ $person->name }}@if(!$loop->last), @endif
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>
                        <td><span class="{{\App\Enums\StatusCaseEnum::from($case->status)->style()}}">
                                {{$case->status}}
                            </span>
                        </td>
                        <td>
                            @php
                                // Verificar se o caso está concluído ou arquivado
                                if ($case->status === 'CONCLUIDO' || $case->status === 'ARQUIVADO') {
                                    $porcentagem = 100;
                                    $cor = 'secondary';
                                    $texto = $case->status === 'CONCLUIDO' ? 'Caso concluído' : 'Caso arquivado';
                                } elseif ($case->date && $case->prazo_dias) {
                                    // Garantir que estamos parseando corretamente a data do banco (formato Y-m-d)
                                    $rawDate = $case->getRawOriginal('date'); // Pega a data original do banco
                                    $dataInicio = \Carbon\Carbon::createFromFormat('Y-m-d', $rawDate)->startOfDay();
                                    $dataLimite = $dataInicio->copy()->addDays((int)$case->prazo_dias);
                                    $hoje = \Carbon\Carbon::now()->startOfDay();
                                    
                                    $diasTotais = (int)$case->prazo_dias;
                                    
                                    // Calcular dias restantes até o prazo limite
                                    $diasRestantes = $hoje->diffInDays($dataLimite, false);
                                    
                                    // Se for negativo, o prazo está vencido
                                    if ($diasRestantes < 0) {
                                        $diasVencidos = abs($diasRestantes);
                                        $vencido = true;
                                        $porcentagem = 100; // Prazo vencido = barra cheia
                                    } else {
                                        $vencido = false;
                                        // Calcular quantos dias já passaram desde o início
                                        $diasDecorridos = $dataInicio->diffInDays($hoje, false);
                                        // Calcular porcentagem do progresso
                                        $porcentagem = min(100, max(0, ($diasDecorridos / $diasTotais) * 100));
                                    }
                                    
                                    // Definir cor e texto baseado na situação
                                    if ($vencido) {
                                        $cor = 'danger'; // Vermelho - prazo vencido
                                        $texto = 'Vencido há ' . $diasVencidos . ' dia(s)';
                                    } elseif ($diasRestantes == 0) {
                                        $cor = 'danger'; // Vermelho - vence hoje
                                        $texto = 'Vence hoje!';
                                        $porcentagem = 100;
                                    } elseif ($diasRestantes <= 2) {
                                        $cor = 'danger'; // Vermelho - crítico (1-2 dias)
                                        $texto = $diasRestantes . ' dia(s) restante(s)';
                                    } elseif ($diasRestantes <= ($diasTotais * 0.3)) {
                                        $cor = 'warning'; // Amarelo - atenção (até 30% do prazo restante)
                                        $texto = $diasRestantes . ' dia(s) restante(s)';
                                    } else {
                                        $cor = 'success'; // Verde - tranquilo (mais de 30% do prazo restante)
                                        $texto = $diasRestantes . ' dia(s) restante(s)';
                                    }
                                } else {
                                    $porcentagem = 0;
                                    $cor = 'secondary';
                                    $texto = 'Sem prazo definido';
                                }
                            @endphp
                            
                            @if($case->status === 'CONCLUIDO' || $case->status === 'ARQUIVADO')
                                <div class="progress mb-1" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $cor }}" role="progressbar" 
                                         style="width: 100%" 
                                         aria-valuenow="100" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-{{ $cor }}">{{ $texto }}</small>
                            @elseif($case->date && $case->prazo_dias)
                                <div class="progress mb-1" style="height: 20px;">
                                    <div class="progress-bar bg-{{ $cor }}" role="progressbar" 
                                         style="width: {{ round($porcentagem, 1) }}%" 
                                         aria-valuenow="{{ round($porcentagem, 1) }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-{{ $cor }}">{{ $texto }}</small>
                            @else
                                <small class="text-muted">{{ $texto }}</small>
                            @endif
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
                                        @if(($case->user_id == $user->id || ($case->sector_id == $user->sector_id && $user->coordinator)) && $case->status !== 'CONCLUIDO' && $case->status !== 'ARQUIVADO')
                                            <a href="{{route('case.edit', $case)}}" class='btn btn-sm mx-1 text-primary'
                                               title='Edição'>
                                                <i class='fa fa-lg fa-fw fa-edit'></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('caso.excluir')
                                        @if($case->user_id == $user->id && $case->status !== 'CONCLUIDO' && $case->status !== 'ARQUIVADO' && !$case->procedures->count())
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

@section('css')
<style>
    .progress {
        border-radius: 10px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .progress-bar {
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    
    .progress-bar.bg-success {
        background: linear-gradient(45deg, #28a745, #20c997);
    }
    
    .progress-bar.bg-warning {
        background: linear-gradient(45deg, #ffc107, #fd7e14);
    }
    
    .progress-bar.bg-danger {
        background: linear-gradient(45deg, #dc3545, #e83e8c);
    }
    
    .progress-bar.bg-secondary {
        background: linear-gradient(45deg, #6c757d, #868e96);
    }
    
    .text-success {
        font-weight: 600;
    }
    
    .text-warning {
        font-weight: 600;
    }
    
    .text-danger {
        font-weight: 600;
    }
    
    .text-secondary {
        font-weight: 600;
        color: #6c757d !important;
    }
</style>
@endsection
