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
            <th class="col-md-2">CPF</th>
            <td class="col-md-4">{{$person['cpf']}}</td>
        </tr>
        <tr>
            <th class="col-md-2">Conta Contrato</th>
            <td class="col-md-4">{{$person['contacontrato']}} </td>
            <th class="col-md-2">Telefone</th>
            <td class="col-md-4">{{$person['telefone']}}</td>
        </tr>
        <tr>
            <th class="col-md-2">Email</th>
            <td class="col-md-4">{{$person['email']}} </td>
            <th class="col-md-2">Endereço</th>
            <td class="col-md-4">{{$person['endereco']}}</td>
        </tr>


        </tbody>
    </table>
</div>
