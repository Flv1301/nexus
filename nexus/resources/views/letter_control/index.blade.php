@extends('adminlte::page')
@section('title','Ofícios')
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)
<x-page-header title="Controle De Ofícios"></x-page-header>
@section('content')
    @include('sweetalert::alert')
    @if($letterControl ?? null)
        @include('letter_control.edit')
    @else
        @include('letter_control.create')
    @endif
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Número',
                    'Usuário',
                    'Unidade',
                    'Setor',
                    'Data',
                    'Destinatário',
                    'Assunto',
                    'Opção'
                ];
                $config = [
                    'order' => false,
                    'columns' => [null, null, null, null, null,null,null, ['orderable' => false]],
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
                                'zeroRecords'=>    "Nenhum dado(s) encontrado(s)",
                                'emptyTable'=>     "Sem dados!",
                            ],
                ];
            @endphp
            <x-adminlte-datatable id="tbl_latters" :heads="$heads" :config="$config" striped hoverable>
                @foreach($listLetterControl  as $letterControl)
                    <tr>
                        <td class="text-lg text-dark">{{$letterControl->number}}</td>
                        <td>{{$letterControl->user->nickname ?? ''}}</td>
                        <td>{{$letterControl->unity->name}}</td>
                        <td>{{$letterControl->sector->name}}</td>
                        <td>{{$letterControl->created_at->format('d/m/Y')}}</td>
                        <td>{{$letterControl->recipient}}</td>
                        <td>{{$letterControl->subject}}</td>
                        <td>
                            <a href="#" class="mr-1" onclick="copyNumber({{$letterControl->number}})"><i
                                    class="fa fa-copy fa-md" style="color: #828a91"></i></a>
                            @if($letterControl->user_id === \Illuminate\Support\Facades\Auth::id() &&
                                    $letterControl->created_at->addDay()->isFuture())
                                <a href="{{ route("letter.control.edit", $letterControl) }}"><i class="fa fa-edit fa-md"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function copyNumber(number) {
            const textArea = document.createElement("textarea");
            textArea.value = number;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: `Nº ${number} Copiado`,
                showConfirmButton: false,
                timer: 1000
            });
        }
    </script>
@endpush
