{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <tr>
            <th class="col-md-2">Nome:</th>
            <td class="col-md-4">{{$person['nome']}} </td>
            <th class="col-md-2">Tipificação</th>
            <td class="col-md-4">{{$person['tipificacao']}}</td>
        </tr>
        <tr>
            <th class="col-md-2">Data de criação</th>
            <td class="col-md-4">{{$person['mes']}}/{{$person['ano']}} </td>
            <th class="col-md-2">Status</th>
            <td class="col-md-4">{{$person['status']}}</td>
        </tr>
        <tr>
            <th class="col-md-2">Localidade:</th>
            <td class="col-md-4">{{$person['localidade']}}</td>
            <th class="col-md-2">Óbito:</th>
            <td class="col-md-4">{{$person['obito']}}</td>
        </tr>
        <tr>
            <th class="col-md-2">Observação:</th>
            <td class="col-md-4">{{$person['observacao']}}</td>

        </tr>
        </tbody>
    </table>
</div>
