@extends('adminlte::page')
@section('title','Pesquisa Bilhetagem')
@section('plugins.Datatables', true)
@section('plugins.TempusDominusBs4', true)
<x-page-header title="Pesquisa de Bilhetagem">
</x-page-header>
@section('content')
    @php $config = ['format' => 'DD/MM/YYYY']; @endphp
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <form action="{{route('ticket.search.ticket')}}" method="POST">
                @csrf
                <div class="d-flex align-items-center">
                    <div class="col-md-4">
                        <x-adminlte-input type="text"
                                          name="numberOrIp"
                                          class="form-control"
                                          id="terms"
                                          label="Pesquisa"
                                          placeholder="Pesquise por número, ip, grupo"
                                          value="{{old('numberOrIp')}}"
                                          required/>
                    </div>
                    <div class="col-md-2">
                        <x-adminlte-select name="column" id="column" label="Coluna">
                            <option value="T">Todos</option>
                            <option value="O">Origem</option>
                            <option value="D">Destino</option>
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-2">
                        <x-adminlte-input-date name="date_start"
                                               class="mask-date"
                                               id="date_start"
                                               :config="$config"
                                               placeholder="Data Início"
                                               label="Data Início"
                                               value="{{old('date_start')}}">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-gradient-warning">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-date>
                    </div>
                    <div class="col-md-2">
                        <x-adminlte-input-date name="date_end"
                                               class="mask-date"
                                               id="date_end"
                                               :config="$config"
                                               placeholder="Data Fim"
                                               label="Data Fim"
                                               value="{{old('date_end')}}">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-gradient-warning">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-date>
                    </div>
                    <div class="mt-md-3">
                        <x-adminlte-button
                            type="submit"
                            icon="fas fa-search"
                            theme="secondary"
                            label="Pesquisar"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header text-md text-info">Chamadas</div>
        <div class="card-body">
            @php
                $heads = [
                    'Caso/Fase',
                    'Origem',
                    'Destino',
                    'Participantes',
                    'IP',
                    'Porta',
                    'Provedor',
                    'Cidade',
                    'Data'
                ];
                $config = [
                    'order' => [[8, 'asc']],
                    'columns' => [null, null, null, null, null, null, null, null, null],
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
            <x-adminlte-datatable id="tbl_calls" :heads="$heads" :config="$config" striped hoverable>
                @isset($tickets['calls'])
                    @foreach($tickets['calls'] as $call)
                        <tr>
                            <td>
                                <a href="{{route('case.analysis', $call->case_id)}}" target="_blank">
                                    {{$call->name}} / {{$call->phase}}
                                </a>
                            </td>
                            <td>{{$call->from}}</td>
                            <td>{{$call->to}}</td>
                            <td>{{$call->participants}}</td>
                            <td>{{$call->from_ip}}</td>
                            <td>{{$call->from_port}}</td>
                            <td>{{$call->provider}}</td>
                            <td>{{$call->city}}</td>
                            <td>{{\Illuminate\Support\Carbon::parse($call->timestamp)->format('d/m/Y H:i')}}</td>
                        </tr>
                    @endforeach
                @endisset
            </x-adminlte-datatable>
        </div>
    </div>
    <div class="card">
        <div class="card-header text-md text-info">Menssagens</div>
        <div class="card-body">
            @php
                $heads = [
                    'Caso/Fase',
                    'Origem',
                    'Destino',
                    'Grupo',
                    'IP',
                    'Porta',
                    'Provedor',
                    'Cidade',
                    'Data'
                ];
            @endphp
            <x-adminlte-datatable id="tbl_messages" :heads="$heads" :config="$config" striped hoverable>
                @isset($tickets['messages'])
                    @foreach($tickets['messages'] as $messages)
                        @php
                            $recipients = explode(',', $messages->recipients);
                            $visibleRecipients = array_slice($recipients, 0, 1);
                            $hiddenRecipients = array_slice($recipients, 1);
                        @endphp
                        <tr>
                            <td>
                                <a href="{{route('case.analysis', $messages->case_id)}}" target="_blank">
                                    {{$messages->name}} / {{$messages->phase}}
                                </a>
                            </td>
                            <td>{{$messages->sender}}</td>
                            <td>
                                {{ implode(',', $visibleRecipients) }}
                                @if (count($recipients) > 1)
                                    <span class="hidden-recipients"
                                          style="display: none;">{{ implode(',', $hiddenRecipients) }}</span>
                                    <a href="#" onclick="toggleRecipients(this); return false;">
                                        <i class="fas fa-sm fa-plus"></i>
                                    </a>
                                @endif
                            </td>
                            <td>{{$messages->group_id}}</td>
                            <td>{{$messages->sender_ip}}</td>
                            <td>{{$messages->sender_port}}</td>
                            <td>{{$messages->provider}}</td>
                            <td>{{$messages->city}}</td>
                            <td>{{\Illuminate\Support\Carbon::parse($messages->timestamp)->format('d/m/Y H:i')}}</td>
                        </tr>
                    @endforeach
                @endisset
            </x-adminlte-datatable>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function toggleRecipients(link) {
            const hiddenRecipients = link.parentElement.querySelector('.hidden-recipients');
            const icon = link.querySelector('i');

            if (hiddenRecipients.style.display === 'none') {
                hiddenRecipients.style.display = 'inline';
                icon.classList.remove('fa-plus');
                icon.classList.add('fa-minus');
            } else {
                hiddenRecipients.style.display = 'none';
                icon.classList.remove('fa-minus');
                icon.classList.add('fa-plus');
            }
        }
    </script>
@endpush
