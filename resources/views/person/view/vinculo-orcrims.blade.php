{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">Vínculos de ORCRIM</h3>
    </div>
    <div class="card-body">
        @if($person->vinculoOrcrims->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Alcunha</th>
                    <th>CPF</th>
                    <th>Tipo de Vínculo</th>
                    <th>Orcrim</th>
                    <th>Cargo</th>
                    <th>Área de Atuação</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->vinculoOrcrims as $vinculo)
                    <tr>
                        <td>{{ $vinculo->name }}</td>
                        <td>{{ $vinculo->alcunha }}</td>
                        <td>{{ $vinculo->cpf }}</td>
                        <td>{{ $vinculo->tipo_vinculo }}</td>
                        <td>{{ $vinculo->orcrim }}</td>
                        <td>{{ $vinculo->cargo }}</td>
                        <td>{{ $vinculo->area_atuacao }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum vínculo de ORCRIM cadastrado.
            </div>
        @endif
    </div>
</div> 