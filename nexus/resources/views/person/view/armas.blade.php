{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">Armas</h3>
    </div>
    <div class="card-body">
        @if($person->armas->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>CAC</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Calibre</th>
                    <th>SINARM</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->armas as $arma)
                    <tr>
                        <td>{{ $arma->cac }}</td>
                        <td>{{ $arma->marca }}</td>
                        <td>{{ $arma->modelo }}</td>
                        <td>{{ $arma->calibre }}</td>
                        <td>{{ $arma->sinarm }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhuma arma cadastrada.
            </div>
        @endif
    </div>
</div> 