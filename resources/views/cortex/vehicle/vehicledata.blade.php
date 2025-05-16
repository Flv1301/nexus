<div class="card">
    <div class="card-body">
        <table class="table table-condensed">
            <tbody>
            <tr>
                <th>Placa</th>
                <td><span class="btn btn-default btn-placa" style="border: 1px solid black; font-weight: bold;
                    font-family: 'Fe Engschrift', Arial, sans-serif;background: linear-gradient( to left, blue 0, blue 40%, white 40%, white 60%, blue 60%, blue ) no-repeat;
                    background-size: 100% 5px;background-color: #ddd;">{{$data->placa}}</span>
                </td>
            </tr>
            <tr>
                <th>Marca/Modelo</th>
                <td><span>{{$data->marcaModelo}}</span></td>
            </tr>
            <tr>
                <th>Ano Fabricação / Ano Modelo</th>
                <td><span>{{$data->anoFabricacao}}</span>/<span>{{$data->anoModelo}}</span></td>
            </tr>
            <tr>
                <th>Cor</th>
                <td><span>{{$data->cor}}</span></td>
            </tr>
            <tr>
                <th>Chassi</th>
                <td><span>{{$data->chassi}}</span></td>
            </tr>
            <tr>
                <th>Renavam</th>
                <td><span>{{$data->renavam}}</span></td>
            </tr>
            <tr>
                <th>Categoria</th>
                <td><span>{{$data->categoria}}</span></td>
            </tr>
            <tr>
                <th>Espécie</th>
                <td><span>{{$data->especie}}</span></td>
            </tr>
            <tr>
                <th>Tipo Veículo</th>
                <td><span>{{$data->tipoVeiculo}}</span></td>
            </tr>
            <tr>
                <th>Nº de Motor</th>
                <td><span>{{$data->numeroMotor}}</span></td>
            </tr>
            <tr>
                <th>UF Emplacamento</th>
                <td><span>{{$data->ufEmplacamento}}</span></td>
            </tr>
            <tr>
                <th>Município Emplacamento</th>
                <td><span>{{$data->municipioPlaca}}</span></td>
            </tr>
            <tr>
                <th>Código Município Emplacamento</th>
                <td><span>{{$data->codigoMunicipioEmplacamento}}</span></td>
            </tr>
            <tr>
                <th>Data Emplacamento</th>
                <td><span>{{\Illuminate\Support\Carbon::parse($data->dataEmplacamento)->format('d/m/Y')}}</span></td>
            </tr>
            <tr>
                <th>Data Último CRV</th>
                <td><span>{{\Illuminate\Support\Carbon::parse($data->dataEmissaoUltimoCRV)->format('d/m/Y')}}</span>
                </td>
            </tr>
            <tr>
                <th>Veículo Nacional</th>
                <td><span>{{$data->indicadorVeiculoNacional == 1 ? 'SIM' : 'NÃO'}}</span></td>
            </tr>
            <tr>
                <th>Grupo Veículo</th>
                <td><span>{{$data->grupoVeiculo}}</span></td>
            </tr>
            <tr>
                <th>Carroceria</th>
                <td><span>{{$data->carroceria}}</span></td>
            </tr>
            <tr>
                <th>Nº Carroceria</th>
                <td><span>{{$data->numeroCarroceria}}</span></td>
            </tr>
            <tr>
                <th>Combustível</th>
                <td><span>{{$data->combustivel}}</span></td>
            </tr>
            <tr>
                <th>Cilindrada</th>
                <td><span>{{$data->cilindrada}}</span></td>
            </tr>
            <tr>
                <th>Remarcado Chassi</th>
                <td><span>{{$data->indicadorRemarcacaoChassi == 1 ? 'SIM' : 'NÃO'}}</span>
                </td>
            </tr>
            <tr>
                <th>Nº Caixa de Câmbio</th>
                <td><span>{{$data->numeroCaixaCambio}}</span></td>
            </tr>
            <tr>
                <th>Em Circulação</th>
                <td><span>{{$data->indicadorVeiculoLicenciadoCirculacao}}</span>
                </td>
            </tr>
            <tr>
                <th>Proprietário</th>
                <td><span>{{$data->proprietario['nomeProprietario']}}</span></td>
            </tr>
            <tr>
                <th>Doc. Proprietario</th>
                <td>
                    <span>{{$data->proprietario['tipoDocumentoProprietario']}}</span>&nbsp;
                    <span>{{$data->proprietario['numeroDocumentoProprietario']}}</span>
                </td>
            </tr>
            <tr>
                <th>Endereço Proprietário</th>
                <td><span>{{$data->proprietario['enderecoProprietario']}}</span>
                </td>
            </tr>
            <tr>
                <th>Possuidor</th>
                <td><span>{{$data->possuidor['nomePossuidor']}}</span></td>
            </tr>
            <tr>
                <th>Doc. Possuidor</th>
                <td><span>{{$data->possuidor['tipoDocumentoPossuidor']}}</span>&nbsp;&nbsp;&nbsp;
                    <span>{{$data->possuidor['numeroDocumentoPossuidor']}}</span></td>
            </tr>
            <tr>
                <th>Endereço Possuidor</th>
                <td><span>{{$data->possuidor['enderecoPossuidor']}}</span></td>
            </tr>
            <tr>
                <th>Restrição Doc. Veiculo</th>
                <td>
                    <p>{{$data->restricaoVeiculo1}}</p>
                    <p>{{$data->restricaoVeiculo2}}</p>
                    <p>{{$data->restricaoVeiculo3}}</p>
                    <p>{{$data->restricaoVeiculo4}}</p>
                </td>
            </tr>
            <tr>
                <th>Quantidade Restrições Emplacamentos</th>
                <td><span>{{$data->quantidadeRestricoesBaseEmplacamento}}</span></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; background-color: #f7f7f7">
                    <span class="text-center text-info">ATUALIZAÇÕES</span>
                </td>
            </tr>
            <tr>
                <th>Data Atualização Veículo</th>
                <td><span>{{\Illuminate\Support\Carbon::parse($data->dataAtualizacaoVeiculo)->format('d/m/Y')}}</span>
                </td>
            </tr>
            <tr>
                <th>Data Atualização Roubo/Furto</th>
                <td>
                    <span>{{\Illuminate\Support\Carbon::parse($data->dataAtualizacaoRouboFurto)->format('d/m/Y')}}</span>
                </td>
            </tr>
            <tr>
                <th>Data Atualização Alarme</th>
                <td><span>{{\Illuminate\Support\Carbon::parse($data->dataAtualizacaoAlarme)->format('d/m/Y')}}</span>
                </td>
            </tr>
            @if($data->restricao)
                <tr>
                    <td colspan="2" style="text-align: center;background-color: #f7f7f7">
                        <span class="text-info">RESTRIÇÕES</span>
                    </td>
                </tr>
                @foreach($data->restricao as $restricao)
                    <tr>
                        <th>Unidade Policial</th>
                        <td><span>{{$restricao['unidadePolicial']}}</span></td>
                    </tr>
                    <tr>
                        <th>Nº BO / ANO</th>
                        <td><span>{{$restricao['numeroBO']}} / {{$restricao['anoBO']}}</span></td>
                    </tr>
                    <tr>
                        <th>Data Ocorrência</th>
                        <td>
                            <span>{{\Illuminate\Support\Carbon::parse($restricao['dataOcorrencia'])->format('d/m/Y H:i')}}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Natureza</th>
                        <td><span>{{$restricao['naturezaOcorrencia']}}</span></td>
                    </tr>
                    <tr>
                        <th>Declarante</th>
                        <td><span>{{$restricao['nomeDeclarante']}}</span></td>
                    </tr>
                    <tr>
                        <th>Contato</th>
                        <td><span>{{$restricao['dddContato']}} - {{$restricao['telefoneContato']}}</span></td>
                    </tr>
                    <tr>
                        <th>Sistema</th>
                        <td><span>{{$restricao['sistema']}}</span></td>
                    </tr>
                @endforeach
            @endif
            @if($data->indiceNacionalVeiculos)
                <tr>
                    <td colspan="2" style="text-align: center;background-color: #f7f7f7">
                        <span class="text-center text-info">INDICE NACIONAL</span>
                    </td>
                </tr>
                @foreach($data->indiceNacionalVeiculos as $indiceNacional)
                    <tr>
                        <th>{{$indiceNacional['metodo']}}</th>
                        <td><span>{{$indiceNacional['qtd']}}</span></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
