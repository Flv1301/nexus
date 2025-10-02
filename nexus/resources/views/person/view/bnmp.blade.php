{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 02/06/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">BNMP</h3>
    </div>
    <div class="card-body">
        @if($person->bnmps->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>N. Mandado</th>
                    <th>Órgão Expedidor</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->bnmps as $bnmp)
                    <tr>
                        <td>{{ $bnmp->numero_mandado }}</td>
                        <td>{{ $bnmp->orgao_expedidor }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum registro de BNMP cadastrado.
            </div>
        @endif
    </div>
</div> 