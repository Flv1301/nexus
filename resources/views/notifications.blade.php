@extends('adminlte::page')
@section('title', 'Notificações')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
@section('content')
    <div class="container-fluid">
        @include('sweetalert::alert')
        <x-page-header title="Notificações"/>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $heads = [
                                    'Mensagem',
                                    'Data',
                                    'Status',
                                    ['label' => 'Marcar', 'no-export' => true],
                                ];
                                $config = [
                                    'order' => [[2, 'asc']],
                                    'columns' => [null, null, null, ['orderable' => false]],
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
                            <x-adminlte-datatable id="notificationsTable" :heads="$heads" :config="$config">
                                @foreach ($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->data['message'] }}</td>
                                        <td>{{ $notification->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $notification->read_at ? 'bg-success' : 'bg-danger' }}">
                                                {{ $notification->read_at ? 'Lida' : 'Não Lida' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if (!$notification->read_at)
                                                <form
                                                    action="{{ route('notifications.mark-as-read', $notification->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-xs btn-link text-success">
                                                        <i class="fas fa-lg fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </x-adminlte-datatable>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
