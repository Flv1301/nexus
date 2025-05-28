{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">Bancário</h3>
    </div>
    <div class="card-body">
        @if($person->bancarios->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Banco</th>
                    <th>Conta</th>
                    <th>Agência</th>
                    <th>Data de Criação</th>
                    <th>Data de Exclusão</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->bancarios as $bancario)
                    <tr>
                        <td>{{ $bancario->banco }}</td>
                        <td>{{ $bancario->conta }}</td>
                        <td>{{ $bancario->agencia }}</td>
                        <td>{{ $bancario->data_criacao }}</td>
                        <td>{{ $bancario->data_exclusao }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum registro bancário cadastrado.
            </div>
        @endif
    </div>
</div> 