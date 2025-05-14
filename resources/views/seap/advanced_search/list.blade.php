{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/09/2023
 * @copyright NIP CIBER-LAB @2023
--}}

@php
    $heads = ['Nome', 'Sexo','Data Nascimento', 'Mãe', 'Situação', ['label' => 'Opções', 'no-export' => true, 'width' => 5]];
    $config = [
        'columns' => [null, null, null, null, null, null],
    ];
@endphp
<div class="card">
    <div class="card-body">
        <x-adminlte-datatable id="tbl_base_sisp" :heads="$heads" :config="$config" striped hoverable>
            @foreach ($results as $key => $result)
                <tr>
                    <td>{{ $result->preso_nome }}</td>
                    <td>{{ $result->preso_sexo }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($result->preso_datanascimento)->format('d/m/Y') }}</td>
                    <td>{{ $result->presofiliacao_mae }}</td>
                    <td>{{ $result->situacaopreso_descricao }}</td>
                    <td>
                        <a href="{{ route('search.advanced.show', ['base' => 'seap', 'id' => $result->id_preso]) }}"
                           class="open-modal-link"><i class="fas fa-lg fa-eye"></i></a>
                        @include('seap.link_map_view')
                    </td>
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
