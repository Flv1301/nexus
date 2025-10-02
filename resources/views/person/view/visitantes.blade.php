{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 15/07/2025
 * @copyright NIP CIBER-LAB @2025
--}}

<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">Visitantes</h3>
    </div>
    <div class="card-body">
        @if($person->visitantes->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Tipo de Vínculo</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->visitantes as $visitante)
                    <tr>
                        <td>{{ $visitante->nome }}</td>
                        <td>{{ $visitante->cpf }}</td>
                        <td>{{ $visitante->tipo_vinculo }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum visitante cadastrado.
            </div>
        @endif
    </div>
</div> 