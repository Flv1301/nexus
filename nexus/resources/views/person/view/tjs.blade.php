{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
@php
    use App\Helpers\StateFlagHelper;
@endphp

<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">Processos</h3>
    </div>
    <div class="card-body">
        @if($person->tjs->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Processo</th>
                    <th>Instância</th>
                    <th>Classe</th>
                    <th>Assunto</th>
                    <th>Autor</th>
                    <th>Recebido em</th>
                    <th>UF</th>
                    <th>Jurisdição</th>
                    <th>Processo Prevento</th>
                    <th>Situação</th>
                    <th>Distribuição</th>
                    <th>Órgão Julgador</th>
                    <th>Órgão Julgador Colegiado</th>
                    <th>Competência</th>
                    <th>Nº Inquérito Policial</th>
                    <th>Valor da Causa</th>
                    <th>Advogado</th>
                    <th>Prioridade</th>
                    <th>Gratuidade</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->tjs as $tj)
                    <tr>
                        <td>
                            @if($tj->uf)
                                {!! StateFlagHelper::getFlagHtml($tj->uf, StateFlagHelper::getStateName($tj->uf)) !!}&nbsp;
                            @endif
                            {{ $tj->processo }}
                        </td>
                        <td>{{ $tj->comarca ?? '' }}</td>
                        <td>{{ $tj->classe ?? '' }}</td>
                        <td>{{ $tj->natureza }}</td>
                        <td>{{ $tj->autor ?? '' }}</td>
                        <td>{{ $tj->data }}</td>
                        <td>{{ $tj->uf }}</td>
                        <td>{{ $tj->jurisdicao ?? '' }}</td>
                        <td>{{ $tj->processo_prevento ?? '' }}</td>
                        <td>{{ $tj->situacao_processo ?? '' }}</td>
                        <td>{{ $tj->distribuicao ?? '' }}</td>
                        <td>{{ $tj->orgao_julgador ?? '' }}</td>
                        <td>{{ $tj->orgao_julgador_colegiado ?? '' }}</td>
                        <td>{{ $tj->competencia ?? '' }}</td>
                        <td>{{ $tj->numero_inquerito_policial ?? '' }}</td>
                        <td>{{ $tj->valor_causa ? 'R$ ' . number_format($tj->valor_causa, 2, ',', '.') : '' }}</td>
                        <td>{{ $tj->advogado ?? '' }}</td>
                        <td>{{ $tj->prioridade === true ? 'Sim' : ($tj->prioridade === false ? 'Não' : '') }}</td>
                        <td>{{ $tj->gratuidade === true ? 'Sim' : ($tj->gratuidade === false ? 'Não' : '') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum processo cadastrado.
            </div>
        @endif
    </div>
</div> 