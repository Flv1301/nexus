{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 04/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        <div>
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <th>Status:</th>
                    <td>{{ $person['pessoa']['statusPessoa'] }}</td>
                    <th>Data Cadastro:</th>
                    <td>{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $person['pessoa']['dataCadastro'])->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Nome:</th>
                    <td>{{ $person['nome'] }}</td>
                    <th>Alcunha:</th>
                    <td>{{ $person['alcunha'] }}</td>
                </tr>
                <tr>
                    <th>Data de nascimento:</th>
                    <td>{{ \Illuminate\Support\Carbon::parse($person['dataNascimento'])->format('d/m/Y') }}</td>
                    <th>Gênero:</th>
                    <td>{{ $person['sexo'] }}</td>
                </tr>
                <tr>
                    <th>Mãe:</th>
                    <td>{{ $person['mae'] }}</td>
                    <th>Pai:</th>
                    <td>{{ $person['pai'] }}</td>
                </tr>
                <tr>
                    <th>Estado civil:</th>
                    <td>{{ $person['estadoCivil'] }}</td>
                    <th>E-mail:</th>
                    <td>{{ $person['email'] }}</td>
                </tr>
                <tr>
                    <th>Município:</th>
                    <td>{{ $person['municipio'] }}</td>
                    <th>UF:</th>
                    <td>{{ $person['uf'] }}</td>
                </tr>
                </tbody>
            </table>
            @include('bnmp.documents')
        </div>
    </div>
</div>
