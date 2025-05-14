{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        @php
            $heads = [
                'BOP',
                'Data Registro',
                'Data do Fato',
                'Unidade',
                'Natureza',
                ['label' => 'Opções', 'no-export' => true, 'width' => 5],
            ];
            $config = [
                'order' => [[0, 'desc']],
                'columns' => [null, null, ['orderable' => false]]
            ];
        @endphp
        <x-adminlte-datatable id="tbl_persons" :heads="$heads" :config="$config" striped hoverable>
            @foreach($bops as $bop)
                <tr>
                    <td>{{ $bop->n_bop }}</td>
                    <td>{{ $bop->dt_registro }}</td>
                    <td>{{ $bop->data_fato }}</td>
                    <td>{{ $bop->unidade_responsavel }}</td>
                    <td>{{ $bop->natureza }}</td>
                    <td><a href="{{route('imei.bop', ['bop' => $bop->bop_bop_key, 'imei' => $imei->imei])}}"><i
                                class="fas fa-eye fa-lg"></i></a></td>
                </tr>
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>
