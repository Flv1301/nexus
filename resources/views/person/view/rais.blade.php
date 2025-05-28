{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">RAIS</h3>
    </div>
    <div class="card-body">
        @if($person->rais->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Empresa/Orgão</th>
                    <th>CNPJ</th>
                    <th>Tipo de Vínculo</th>
                    <th>Admissão</th>
                    <th>Situação</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->rais as $rais)
                    <tr>
                        <td>{{ $rais->empresa_orgao }}</td>
                        <td>{{ $rais->cnpj }}</td>
                        <td>{{ $rais->tipo_vinculo }}</td>
                        <td>{{ $rais->admissao }}</td>
                        <td>{{ $rais->situacao }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum registro de RAIS cadastrado.
            </div>
        @endif
    </div>
</div> 