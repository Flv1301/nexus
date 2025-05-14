{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        @php
            $heads = [
                'Nome',
                'Contato',
                'Número',
                'Caso',
                'Unidade',
                'Setor'
            ];
            $config = [
                'order' => [[0, 'asc']],
                'columns' => [null, null, null, null, null, null],
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
        <x-adminlte-datatable id="tbl_vcards" :heads="$heads" :config="$config" striped hoverable>
            @foreach($vcards ?? [] as $vcard)
                <tr>
                    <td>
                        {{$vcard->name}}
                        <a href="{{route('person.show', $vcard->id)}}" class="ml-2"><i class="fas fa-eye"></i></a>
                    </td>
                    <td>{{$vcard->fullname}}</td>
                    <td>{{$vcard->number}}</td>
                    <td>
                        {{$vcard->case_name}}
                        @if($vcard->case_id)
                            <a href="{{route('case.analysis', $vcard->case_id)}}" class="ml-2"><i class="fas fa-eye"></i></a>
                        @endif
                    </td>
                    <td>{{$vcard->unity_name}}</td>
                    <td>{{$vcard->sector_name}}</td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
