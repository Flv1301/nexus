{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <tr>
            <th>Nome</th>
            <td>{{$person['nomeCompleto']}}</td>
            <th>CPF</th>
            <td>{{$person['numeroCPF']}}</td>
        </tr>
        <tr>
            <th>Data de Nascimento</th>
            <td>{{\Illuminate\Support\Carbon::parse($person['dataNascimento'])->format('d/m/Y')}}</td>
            <th>Naturalidade</th>
            <td>{{$person['municipioNaturalidade']}}</td>
        </tr>
        <tr>
            <th>Nome da Mãe</th>
            <td>{{$person['nomeMae']}}</td>
            <th>Nome do Pai</th>
            <td>{{$person['nomePai'] ?? ''}}</td>
        </tr>
        <tr>
            <th>Endereço</th>
            <td colspan="3">{{$person['logradouro']}} {{$person['numeroLogradouro']}} {{$person['bairro']}} {{$person['municipio']}} {{$person['uf']}}</td>
        </tr>
        <tr>
            <th>Complemento</th>
            <td colspan="3">{{$person['complementoLogradouro']}}</td>
        </tr>
        <tr>
            <th>Atualizado</th>
            <td colspan="3">{{\Illuminate\Support\Carbon::parse($person['dataAtualizacao'])->format('d/m/Y')}}</td>
        </tr>
        </tbody>
    </table>
</div>

