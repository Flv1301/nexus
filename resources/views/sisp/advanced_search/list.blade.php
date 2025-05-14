{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 13/09/2023
 * @copyright NIP CIBER-LAB @2023
--}}

@php
    $heads = ['SISP', 'BOP', 'Data Registro', 'Data do Fato', 'Unidade Responsável',['label' => 'Opções', 'no-export' => true, 'width' => 5]];
    $config = [
        'columns' => [null, null, null, null, null, ['orderable' => false]],
    ];
@endphp
<div class="card">
    <div class="card-body">
        <x-adminlte-datatable id="tbl_base_sisp" :heads="$heads" :config="$config" striped hoverable>
            @foreach ($results as $key => $result)
                <tr>
                    <td>{{ \Illuminate\Support\Str::after($result->sisp, 'SISP') }}</td>
                    <td>{{ $result->bop ?? '' }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($result->data_registro)->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $result->data_fato }}</td>
                    <td>{{ $result->unidade_responsavel }}</td>
                    <td><a href="{{ route('search.advanced.show', ['base' => 'sisp', 'id' => $result->bop_id]) }}"
                            class="open-modal-link"><i class="fas fa-lg fa-eye"></i></a></td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
        <div class="d-flex justify-content-end mt-3">
            <div class="pagination">
                {{ $results->links('vendor.pagination.bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
