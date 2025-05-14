{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/03/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Logs de atividades')
@section('plugins.Datatables', true)
@section('plugins.TempusDominusBs4', true)
@php
    $config = ['format' => 'DD/MM/YYYY'];
@endphp
<x-page-header title="Logs de Atividades de Usuário">
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <form action="{{route('log.search')}}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <x-adminlte-select id="user_id"
                                           name="user_id"
                                           label="Usuário"
                                           placeholder="Escolha uma opção">
                            <option></option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->nickname}}</option>
                            @endforeach
                        </x-adminlte-select>
                    </div>
                    <div class="form-group col-md-3">
                        <x-adminlte-select id="event"
                                           name="event"
                                           label="Tipo de Ação"
                                           placeholder="Escolha uma opção">
                            <option></option>
                            <option value="created">Cadastro</option>
                            <option value="updated">Atualização</option>
                            <option value="deleted">Exclusão</option>
                        </x-adminlte-select>
                    </div>
                    <div class="form-group col-md-3">
                        <x-adminlte-select id="log_name"
                                           name="log_name"
                                           label="Nome de Logs"
                                           placeholder="Escolha uma opção">
                            <option></option>
                            @foreach($logNames as $logName)
                                <option value="{{$logName}}">{{$logName}}</option>
                            @endforeach
                        </x-adminlte-select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <x-adminlte-input-date name="from"
                                               class="mask-date"
                                               id="from"
                                               :config="$config"
                                               placeholder="Data Início"
                                               label="Data Início">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-gradient-warning">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-date>
                    </div>
                    <div class="form-group col-md-4">
                        <x-adminlte-input-date name="to"
                                               class="mask-date"
                                               id="to"
                                               :config="$config"
                                               placeholder="Data Fim"
                                               label="Data Fim">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-gradient-warning">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-date>
                    </div>
                </div>
                <div class="ml-2">
                    <x-adminlte-button
                        type="submit"
                        icon="fas fa-search"
                        theme="secondary"
                        label="Pesquisar"/>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Data',
                    'Usuário',
                    'Modelo',
                    'Evento',
                    'Visualizar'
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
            <x-adminlte-datatable id="tbl_logs" :heads="$heads" :config="$config" striped hoverable>
                @foreach($logs ?? [] as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->causer->name ?? '' }}</td>
                        <td>{{ $log->log_name ?? ''}}</td>
                        <td>{{ $log->description ?? ''}}</td>
                        <td><a href="{{route('log.show', $log)}}"><i class="far fa-eye fa-lg"></i></a></td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@endsection
