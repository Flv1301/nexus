{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">TJ</h3>
    </div>
    <div class="card-body">
        @if($person->tjs->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Processo</th>
                    <th>Natureza</th>
                    <th>Data</th>
                    <th>UF</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->tjs as $tj)
                    <tr>
                        <td>{{ $tj->processo }}</td>
                        <td>{{ $tj->natureza }}</td>
                        <td>{{ $tj->data }}</td>
                        <td>{{ $tj->uf }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum registro de TJ cadastrado.
            </div>
        @endif
    </div>
</div> 