{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@php
    $heads = ['Data', 'Movimentação','Tipificação Penal', 'Descrição'];
    $config = [
        'order' => [[0, 'asc']],
        'columns' => [null, null, null, null],
        'paging' => true,
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
<x-adminlte-datatable id="tbl_base_sisp_moviments" :heads="$heads" :config="$config" striped hoverable>
    @foreach ($result->moviments as $key => $result)
        <tr>
            <td>{{ $result->movimentacao_data }}</td>
            <td>{{ $result->tipomovimentacao_descricao }}</td>
            <td>{{ $result->movimentacaoentradatipificacaopenal_descricao }}</td>
            <td>{{ $result->movimentacaoobservacao_descricao }} </td>
        </tr>
    @endforeach
</x-adminlte-datatable>
