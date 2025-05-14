{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/09/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="container mt-4">
    <div class="table-responsive">
        <table class="table table-striped">
            <tbody>
            <tr>
                <th class="data-label">Nome do Preso</th>
                <td>{{$result->preso_nome}}</td>
                <th class="data-label">Alcunha</th>
                <td>{{$result->presoalcunha_descricao}}</td>
            </tr>
            <tr>
                <th class="data-label">Sexo</th>
                <td>{{$result->preso_sexo}}</td>
                <th class="data-label">Data de Nascimento</th>
                <td>{{$result->preso_datanascimento}}</td>
            </tr>
{{--            <tr>--}}
{{--                <th class="data-label">Documento</th>--}}
{{--                <td colspan="3">{{$result->document}}</td>--}}
{{--            </tr>--}}
            <tr>
                <th class="data-label">Mãe</th>
                <td>{{$result->presofiliacao_mae}}</td>
                <th class="data-label">Pai</th>
                <td>{{$result->presofiliacao_pai}}</td>
            </tr>
            <tr>
{{--                <th class="data-label">Documento do Preso</th>--}}
{{--                <td>{{$result->presodocumento_numero}}</td>--}}
                <th class="data-label">Logradouro</th>
                <td>{{$result->enderecopreso_logradouro}}</td>
            </tr>
            <tr>
                <th class="data-label">Bairro</th>
                <td>{{$result->enderecopreso_bairro}}</td>
                <th class="data-label">Município</th>
                <td>{{$result->municipio_nome}}</td>
            </tr>
            <tr>
                <th class="data-label">UF</th>
                <td>{{$result->municipio_siglauf}}</td>
                <th class="data-label">Distrito</th>
                <td>{{$result->distrito_nome}}</td>
            </tr>
            <tr>
                <th class="data-label">Telefone</th>
                <td>{{$result->telefone_numero}}</td>
                <th class="data-label">Altura</th>
                <td>{{$result->altura_descricao}}</td>
            </tr>
            <tr>
                <th class="data-label">Cor/Etnia</th>
                <td>{{$result->corpeleetnia_descricao}}</td>
                <th class="data-label">Cor dos Olhos</th>
                <td>{{$result->corolhos_descricao}}</td>
            </tr>
            <tr>
                <th class="data-label">Tipo de Cabelo</th>
                <td>{{$result->tipocabelo_descricao}}</td>
                <th class="data-label">Cor dos Cabelos</th>
                <td>{{$result->corcabelos_descricao}}</td>
            </tr>
            <tr>
                <th class="data-label">Situação do Preso</th>
                <td>
                    {{$result->situacaopreso_descricao}}
                    @include('seap.link_map_view')
                </td>
                <th class="data-label">Data da Última Prisão</th>
                <td>{{$result->preso_dataultimaprisao}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
