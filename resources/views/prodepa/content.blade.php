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
            <td class="col-md-4">{{$person['nome']}} {{$person['sobrenome']}}</td>
            <th class="col-md-2">Registro Geral:</th>
            <td class="col-md-4">{{$person['registroGeral']}}</td>
        </tr>
        <tr>
            <th class="col-md-2">Data de Nascimento:</th>
            <td class="col-md-4">{{\Illuminate\Support\Carbon::createFromFormat('dmY',$person['dataNascimento'])->format('d/m/Y')}}</td>
            <th class="col-md-2">Nome do Pai:</th>
            <td class="col-md-4">{{$person['pai']}}</td>
        </tr>
        <tr>
            <th class="col-md-2">Nome do Mãe:</th>
            <td class="col-md-4">{{$person['mae']}}</td>
            <th class="col-md-2">Naturalidade:</th>
            <td class="col-md-4">{{$person['naturalidade']}}</td>
        </tr>
        <tr>
            <th class="col-md-2">Endereço:</th>
            <td class="col-md-4" colspan="3">{{$person['address']->address}} - {{$person['address']->number}}
                , {{$person['address']->district}}, {{$person['address']->city}} - {{$person['address']->uf}}</td>
        </tr>
        </tbody>
    </table>
</div>
