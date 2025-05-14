{{--
* @author Herbety Thiago Maciel
* @version 1.0
* @since 27/12/2022
* @copyright NIP CIBER-LAB @2022
--}}
@php $collections = new \Illuminate\Support\Collection(); @endphp
@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.Chartjs', true)
@section('title','Log de Acesso Whatsapp')

<x-page-header title="Log de Acesso Whatsapp ({{$file->account_identifier}})"/>
@section('content')
    @include('sweetalert::alert')
    <div class="card card-warning card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#tab_data" data-toggle="pill" role="tab" class="nav-link active">Dados</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_ip_address" data-toggle="pill" role="tab" class="nav-link">IP's</a>
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
                @php
                    $heads = [
                        'IP',
                        'Data/Hora (UTC)',
                        'Empresa',
                        'Estado',
                    ];
                    $config = [
                        'order' => [[0, 'asc']],
                        'columns' => [null,null,null,null],
                        ];
                @endphp
                <div class="tab-pane" id="tab_ip_address" role="tabpanel">
                    <x-adminlte-datatable id="tab_ip"
                                          :heads="$heads"
                                          :config="$config"
                                          striped hoverable
                                          with-buttons>
                        @foreach($file->acesslogIpAddress->load('ipInfo') as $ip)
                            @php $collections->add($ip); @endphp
                            <tr>
                                <td>{{$ip->ip_address}}</td>
                                <td class="text-sm">{{$ip->time}}</td>
                                <td class="text-sm">{{$ip->ipInfo->name ?? $ip->ipInfo->org ?? ''}}</td>
                                <td class="text-sm">{{$ip->ipInfo->region ?? ''}}</td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
                <div class="tab-pane" id="tab_chart" role="tabpanel">
                    <div class="card">
                        <div class="card-footer">
                            <span>Gráfico com até 5 conexões mais efetuadas</span>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart" style="min-height: 450px; height: 450px;
                            max-height: 450px; max-width: 100%; display: block;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@php
    $ips = $collections->countBy('ip_address')->sortDesc()->take(5);
@endphp
@push('js')
    <script>
        $(function () {
            let colors = [];
            for (let i = 0; i < {{$ips->count()}}; i++) {
                const r = Math.floor(Math.random() * 256);
                const g = Math.floor(Math.random() * 256);
                const b = Math.floor(Math.random() * 256);
                colors.push("rgb(" + r + ", " + g + ", " + b + ")");
            }
            const pieChartCanvas = document.getElementById('pieChart');
            let pieData = {
                labels: {!! $ips->keys() !!},
                datasets: [
                    {
                        data: {!! $ips->values() !!},
                        backgroundColor: colors,
                    }
                ]
            };
            let pieOptions = {
                maintainAspectRatio: false,
                responsive: true,
            };
            new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
            });
        });
    </script>
@endpush
