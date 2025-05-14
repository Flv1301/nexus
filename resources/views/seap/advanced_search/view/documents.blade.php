{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@php
    $heads = ['Documentos'];
    $config = [
        'columns' => [null],
        'paging' => false,
        'language' => [
            'paginate' => [
                'first' => 'Primeiro',
                'last' => 'Último',
                'next' => 'Próximo',
                'previous' => 'Anterior',
            ],
            'search' => 'Pesquisar na Tabela',
            'lengthMenu' => 'Mostrar  _MENU_  Resultados',
            'info' => 'Mostrando _START_ a _END_ de _TOTAL_ Resultados.',
            'infoEmpty' => 'Mostrando 0 Resultados.',
            'infoFiltered' => '(Filtro de _MAX_ Resultados no total)',
            'loadingRecords' => 'Pesquisando...',
            'zeroRecords' => 'Nem um dado(s) encontrado(s)',
            'emptyTable' => 'Sem dados!',
        ],
    ];
@endphp
<x-adminlte-datatable id="tbl_base_sisp_peculiaritys" :heads="$heads" :config="$config" striped hoverable>
    @foreach ($result->documents as $key => $result)
        <tr>
            <td>CPF: {{ $result->cpf_numeo }}</td>
        </tr>
        <tr>
            <td>RG: {{ $result->rg_numero }}</td>
        </tr>
        <tr>
            <td>CNH: {{ $result->cnh_numero }}</td>
        </tr>
        <tr>
            <td>Passaporte: {{$result->passaporte_numero}} </td>
        </tr>
        <tr>
            <td>Carteira Trabalho: {{$result->carteiratrabalho_numero}} </td>
        </tr>
        <tr>
            <td>Titulo Eleitor: {{$result->titulo_eleitor_numero}}</td>
        </tr>
    @endforeach
</x-adminlte-datatable><?php
