{{--
* @author Herbety Thiago Maciel
* @version 1.0
* @since 27/12/2022
* @copyright NIP CIBER-LAB @2022
--}}
@php $collections = new \Illuminate\Support\Collection(); @endphp
@extends('adminlte::page')
@section('title','Whatsapp Bilhetagem')
@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
<x-page-header title="Extrato Bilhetagem Whatsapp ({{$file->account_identifier}})">
    {{--    <a href="{{route('case.create', $file)}}" class="btn btn-success">Exportar Csv</a>--}}
</x-page-header>
@section('content')
    @include('sweetalert::alert')
    <div class="card card-warning card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#tab_data" data-toggle="pill" role="tab" class="nav-link active">Dados</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_messages" data-toggle="pill" role="tab" class="nav-link">Mensagens</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_calls" data-toggle="pill" role="tab" class="nav-link">Chamadas</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_chart" data-toggle="pill" role="tab" class="nav-link">Gráfico</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane active" id="tab_data" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <span>Tipo: </span>
                                <span class="text-info">{{$file->name}}</span>
                            </div>
                            <div class="form-group">
                                <span>Identificador: </span>
                                <span class="text-info">{{$file->account_identifier}}</span>
                            </div>
                            <div class="form-group">
                                <span>Período Solicitação: </span>
                                <span class="text-info">{{$file->date_range}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_calls" role="tabpanel">
                    @php
                        $heads = [
                            'Origem',
                            'Destino',
                            'Data/Hora (UTC)',
                            'IP',
                            'Porta',
                            'Empresa',
                            'Estado',
                        ];
                        $config = [
                            'order' => [[2, 'asc']],
                            'columns' => [null,null,null,null,null,null,null]
                            ];
                    @endphp
                    <x-adminlte-datatable id="tbl_calls" :heads="$heads" :config="$config" striped hoverable
                                          with-buttons>
                        @foreach($file->calls->load('events.ipInfo') as $call)
                            @foreach($call->events as $event)
                                @php $collections->add($event); @endphp
                                <tr>
                                    <td><span class="badge badge-light">{{$event->from}}</span></td>
                                    <td><span class="badge badge-light">{{$event->to}}</span></td>
                                    <td class="text-sm">{{$event->timestamp}}</td>
                                    <td class="text-sm">{{$event->from_ip}}</td>
                                    <td class="text-sm">{{$event->from_port}}</td>
                                    <td class="text-sm">{{$event->ipInfo->name ?? $event->ipInfo->org ?? ''}}</td>
                                    <td class="text-sm">{{$event->ipInfo->region ?? ''}}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane" id="tab_messages" role="tabpanel">
                    @php
                        $heads = [
                            'Origem',
                            'Destino',
                            'Data/Hora (UTC)',
                            'Grupo',
                            'IP',
                            'Porta',
                            'Empresa',
                            'Estado',
                        ];
                        $config = [
                            'order' => [[2, 'asc']],
                            'columns' => [null,null,null,null,null,null,null, null]
                            ];
                    @endphp
                    <x-adminlte-datatable id="tbl_messages" :heads="$heads" :config="$config" striped hoverable
                                          with-buttons>
                        @foreach($file->messages->load('ipInfo') as $message)
                            @php
                                $recipients = explode(',', $message->recipients);
                                $visibleRecipients = array_slice($recipients, 0, 1);
                                $hiddenRecipients = array_slice($recipients, 1);
                            @endphp
                            <tr>
                                <td><span class="badge badge-light">{{$message->sender}}</span></td>
                                <td>
                                    {{implode(',', $visibleRecipients)}}
                                    @if(count($recipients) > 1)
                                        <span id="hiddenRecipients"
                                              style="display: none;">{{implode(',', $hiddenRecipients)}}</span>
                                        <a href="#" id="toggleLink" onclick="toggleRecipients(); return false;"><i
                                                class="fas fa-plus"></i></a>
                                    @endif
                                </td>
                                <td class="text-sm">{{$message->timestamp}}</td>
                                <td class="text-sm">{{$message->group_id}}</td>
                                <td class="text-sm">{{$message->sender_ip}}</td>
                                <td class="text-sm">{{$message->sender_port}}</td>
                                <td class="text-sm">{{$message->ipInfo->name ?? $message->ipInfo->org ?? ''}}</td>
                                <td class="text-sm">{{$message->ipInfo->region ?? ''}}</td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane" id="tab_chart" role="tabpanel">
                    <div class="card">
                        <div class="card-footer">
                            <span>LIGAÇÕES</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-footer">
                                        <span>Gráfico com até 5 ligações mais efetuadas</span>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="pieChartTo" style="min-height: 450px; height: 450px;
                                            max-height: 450px; max-width: 100%; display: block;">
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-footer">
                                        <span>Gráfico com até 5 ligações mais recebidas</span>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="pieChartFrom" style="min-height: 450px; height: 450px;
                                            max-height: 450px; max-width: 100%; display: block;">
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@php
    $to = $collections->countBy('to')->sortDesc()->take(5);
    $from = $collections->countBy('from')->sortDesc()->take(5);
@endphp
@push('js')
    <script>
        $(function () {
            let colors = [];
            for (let i = 0; i < {{$to->count()}}; i++) {
                var r = Math.floor(Math.random() * 256);
                var g = Math.floor(Math.random() * 256);
                var b = Math.floor(Math.random() * 256);
                colors.push("rgb(" + r + ", " + g + ", " + b + ")");
            }
            const pieChartTo = document.getElementById('pieChartTo');
            let pieDataTo = {
                labels: {!! $to->keys() !!},
                datasets: [
                    {
                        data: {!! $to->values() !!},
                        backgroundColor: colors,
                    }
                ]
            };
            let pieOptionsTo = {
                maintainAspectRatio: false,
                responsive: true,
            };
            new Chart(pieChartTo, {
                type: 'pie',
                data: pieDataTo,
                options: pieOptionsTo
            });

            const pieChartFrom = document.getElementById('pieChartFrom');
            let pieDataFrom = {
                labels: {!! $from->keys() !!},
                datasets: [
                    {
                        data: {!! $from->values() !!},
                        backgroundColor: colors,
                    }
                ]
            };
            let pieOptionsFrom = {
                maintainAspectRatio: false,
                responsive: true,
            };
            new Chart(pieChartFrom, {
                type: 'pie',
                data: pieDataFrom,
                options: pieOptionsFrom
            });
        });
    </script>
@endpush
@push('js')
    <script>
        function toggleRecipients() {
            var hiddenRecipients = document.getElementById('hiddenRecipients');
            var toggleLink = document.getElementById('toggleLink');
            var icon = toggleLink.querySelector('i');

            if (hiddenRecipients.style.display === 'none') {
                hiddenRecipients.style.display = 'block';
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
