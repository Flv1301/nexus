{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title', 'Pesquisa no Relato')
@section('plugins.Datatables', true)
<x-page-header title="Pesquisa Relato"/>
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pesquisa.relato.elastic') }}" method="post" id="form-search">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered" id="table-search">
                        <thead>
                        <tr>

                            <th class="text-truncate">Campo</th>
                            <th style="width: 200px;" class="text-truncate">Operador</th>
                            <th class="text-truncate">Valor</th>
                            <th style="width: 60px;" class="text-truncate">Ação</th>
                        </tr>
                        </thead>
                        <tbody id="table-tbody">
                        <tr>


                            <td id="column-select" class="align-middle">
                                <select class="form-control column-select" name="columns[]"
                                       >
                                    <option value="relato">relato</option>
                                    <option value="bop_bop_key">bop_bop_key</option>
                                    <option value="sisp">sisp</option>
                                    <option value="n_bop">n_bop</option>
                                    <option value="unidade_responsavel">unidade_responsavel</option>
                                    <option value="registros">registros</option>
                                    <option value="dt_registro">dt_registro</option>
                                    <option value="data_fato">data_fato</option>
                                    <option value="natureza">natureza</option>
                                    <option value="ds_bairro_fato">ds_bairro_fato</option>
                                    <option value="meio_empregado">meio_empregado</option>
                                    <option value="localgp_ocorrencia">localgp_ocorrencia</option>
                                    <option value="localidade_fato">localidade_fato</option>
                                    <option value="latitude_fato">latitude_fato</option>
                                    <option value="longitude_fato">longitude_fato</option>
                                    <option value="sigiloso">sigiloso</option>
                                    <option value="dt_fato">dt_fato</option>
                                    <option value="bopenv.nm_envolvido">nm_envolvido</option>
                                    <option value="nascimento">nascimento</option>
                                    <option value="sexo">sexo</option>
                                    <option value="estado_civil">estado_civil</option>
                                    <option value="mae">mae</option>
                                    <option value="pai">pai</option>
                                    <option value="naturalidade">naturalidade</option>
                                    <option value="nacionalidade">nacionalidade</option>
                                    <option value="instrucao">instrucao</option>
                                    <option value="profissao">profissao</option>
                                    <option value="cpf">cpf</option>
                                    <option value="contato">contato</option>
                                    <option value="localidade">localidade</option>
                                    <option value="bairro">bairro</option>
                                    <option value="endereco">endereco</option>
                                    <option value="uf">uf</option>
                                    <option value="cep">cep</option>
                                    <option value="ds_atuacao">ds_atuacao</option>
                                </select>
                            </td>
                            <td class="align-middle">
                                <select name="operators[]" class="form-control">
                                    <option value="query_string">Contém</option>
                                    <option value="match">Termo único(radical)</option>
                                    <option value="match_phrase">Contém exatamente (Frase)</option>
                                    <option value="wildcard">Coringa</option>

                                    <option value="term">Igual</option>
                                    <option value="must_not">Diferente</option>
                                    <option value="range:gt">Maior</option>
                                    <option value="range:lt">Menor</option>
                                    <option value="range:gte">Maior ou Igual</option>
                                    <option value="range:lte">Menor ou Igual</option>
                                    <option value="prefix">Prefixo</option>
                                </select>
                            </td>
                            <td class="align-middle"><input id="values" name="values[]"
                                                            type="text"
                                                            class="form-control text-uppercase"
                                                            placeholder=""/></td>
                            <td class="align-middle"><a onclick="removeRow(this)"
                                                        class="btn btn-sm btn-danger"><i
                                        class="fa fa-trash-alt"></i></a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="base" name="base">
                <div class="d-flex justify-content-end">
                    <x-adminlte-button theme="success" icon="fa fa-plus" class="mr-2"
                                       id="btn-plus" onclick="appendRow()"/>
                </div>

                <div class="card-footer">
                    <div>
                        <x-adminlte-button label="Pesquisar" theme="primary" icon="fa fa-search"
                                           id="btn-search" type="submit"/>
                    </div>
                </div>
                <span class="text-info mt-2">* Selecione o Banco De Dados Para Pesquisar</span>
            </form>
        </div>
    </div>
    @isset($bops)
        @if(count($bops) > 0)
            <div class="card">
                <div class="card-body">
                    @php
                        $heads = [
                            'BOP',
                            'Data do Fato',
                            'Unidade Responsavel',
                            ['label' => 'Opções', 'no-export' => true, 'width' => 5],
                        ];
                        $config = [
                            'order' => [[0, 'desc']],
                            'columns' => [null, null, null, ['orderable' => false]],
                            'language' => [
                                'paginate' => [
                                    'first' => 'Primeiro',
                                    'last' => 'Último',
                                    'next' => 'Próximo',
                                    'previous' => 'Anterior',
                                ],
                                'search' => 'Pesquisar na Tabela',
                                'lengthMenu'=>    "Mostrar  _MENU_  Resultados",
                                'info'=>           "Mostrando _START_ a _END_ de _TOTAL_ Resultados.",
                                'infoEmpty'=>      "Mostrando 0 Resultados.",
                                'infoFiltered'=>   "(Filtro de _MAX_ Resultados no total)",
                                'loadingRecords'=> "Pesquisando...",
                                'zeroRecords'=>    "Nem um dado(s) encontrado(s)",
                                'emptyTable'=>     "Sem dados!",
                            ],
                            'dom' => 'Bfrtip', // Adiciona botões de exportação
                            'buttons' => ['excel', 'pdf', 'csv'], // Tipos de exportação
                        ];
                    @endphp
                    <x-adminlte-datatable id="tbl_persons" :heads="$heads" :config="$config" striped hoverable>
                        @foreach($bops as $bop)
                            <tr>
                                <td>{{$bop['_source']['n_bop']}}</td>
                                <td>{{$bop['_source']['data_fato']}}</td>
                                <td>{{$bop['_source']['unidade_responsavel']}}</td>
                                <td>
                                    <div class="d-flex justify-content-between">
                                       <a href="{{ route('pesquisa.relato.show', ['bop' => $bop['_source']['bop_bop_key']]) }}" title="Visualizar"><i class="fas fa-lg fa-eye"></i></a>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                    <form action="{{ route('pesquisa.relato.export.word') }}" method="post">
                        @csrf
                        <input type="hidden" name="bops" value="{{ json_encode($bops) }}">

                        <button type="submit">Exportar para Word</button>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <span class="text-lg text-info"> <i class="fas fa-info-circle fa-lg"></i> Sem registro na base de dados para o dado pesquisado!</span>
                </div>
            </div>
        @endif
    @endisset



@endsection

@push('js')
    <script>

        function appendRow() {
            var newRow = $("<tr>");
            var cols = "";

            cols += '<td class="align-middle"><select class="form-control column-select" name="columns[]" onchange="placeholder(this)"></select></td>';
            cols += '<td class="align-middle"><select name="operators[]" class="form-control">' +
                '<option value="query_string">Contém</option>' +
                '<option value="match">Termo único (radical)</option>' +
                '<option value="match_phrase">Contém exatamente (Frase)</option>' +

                '<option value="wildcard">Coringa</option>' +
                '<option value="term">Igual</option>' +
                '<option value="must_not">Diferente</option>' +
                '<option value="range:gt">Maior</option>' +
                '<option value="range:lt">Menor</option>' +
                '<option value="range:gte">Maior ou Igual</option>' +
                '<option value="range:lte">Menor ou Igual</option>' +
                '<option value="prefix">Prefixo</option>' +

                '</select></td>';
            cols += '<td class="align-middle"><input name="values[]" type="text" class="form-control text-uppercase" placeholder=""/></td>';
            cols += '<td class="align-middle"><a class="btn btn-sm btn-danger remove-row"><i class="fa fa-trash-alt"></i></a></td>';

            newRow.append(cols);
            $("#table-search").append(newRow);

            // Adiciona opções ao select "columns[]" na nova linha
            var options = [
                "relato", "bop_bop_key", "sisp", "n_bop", "unidade_responsavel", "registros", "dt_registro",
                "data_fato", "natureza", "ds_bairro_fato", "meio_empregado", "localgp_ocorrencia",
                "localidade_fato", "latitude_fato", "longitude_fato", "sigiloso", "dt_fato", "bopenv.nm_envolvido",
                "nascimento", "sexo", "estado_civil", "mae", "pai", "naturalidade", "nacionalidade",
                "instrucao", "profissao", "cpf", "contato", "localidade", "bairro", "endereco", "uf", "cep",
                "ds_atuacao"
            ];
            var select = newRow.find('.column-select');
            for (var i = 0; i < options.length; i++) {
                select.append('<option value="' + options[i] + '">' + options[i] + '</option>');
            }
        }

        // Função para remover uma linha da tabela
        $(document).on("click", ".remove-row", function () {
            $(this).closest("tr").remove();
        });
    </script>
@endpush
