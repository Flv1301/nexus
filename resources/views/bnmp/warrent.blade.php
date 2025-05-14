{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 04/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="container">
    @foreach($person['mandadoPrisao'] as $arrestWarrant)
        <div class="card">
            <div class="card-header">
                <div>
                    <strong>Status: </strong><span class="text-info">{{$arrestWarrant['status']}}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 200px;">Número Processo</th>
                            <td>{{$arrestWarrant['numeroProcesso']}}</td>
                            <th style="width: 200px;">Data Cadastro</th>
                            <td>{{\Illuminate\Support\Carbon::parse($arrestWarrant['dataCriacao'])->format('d/m/Y')}}</td>
                        </tr>
                        <tr>
                            <th>Numero Peça</th>
                            <td>{{$arrestWarrant['numeroPeca']}}</td>
                            <th>Data Expedicão</th>
                            <td>{{\Illuminate\Support\Carbon::parse($arrestWarrant['dataExpedicao'])->format('d/m/Y')}}</td>
                        </tr>
                        <tr>
                            <th>Tipo Peça</th>
                            <td>{{$arrestWarrant['tipoPeca']}}</td>
                            <th>Data Prisão</th>
                            <td>{{\Illuminate\Support\Carbon::parse($arrestWarrant['dataPrisao'])->format('d/m/Y')}}</td>
                        </tr>
                        <tr>
                            <th>Regime Prisional</th>
                            <td>{{$arrestWarrant['regimePrisional']}}</td>
                            <th>Data Conclusão</th>
                            <td>{{\Illuminate\Support\Carbon::parse($arrestWarrant['dataConclusao'])->format('d/m/Y')}}</td>
                        </tr>
                        <tr>
                            <th>Sigilo</th>
                            <td>{{$arrestWarrant['sigilo']}}</td>
                            <th>Data Validade</th>
                            <td>{{\Illuminate\Support\Carbon::parse($arrestWarrant['dataValidade'])->format('d/m/Y')}}</td>
                        </tr>
                        <tr>
                            <th>Orgão Judiciário</th>
                            <td colspan="3">{{$arrestWarrant['orgaoJudiciario']}}</td>
                        </tr>
                        <tr>
                            <th>Especie Prisão</th>
                            <td colspan="3">{{$arrestWarrant['especiePrisao']}}</td>
                        </tr>
                        <tr>
                            <th>Sintese Decisão</th>
                            <td colspan="3">{{$arrestWarrant['sinteseDecisao']}}</td>
                        </tr>
                        <tr>
                            <th>Descricão Cumprimento</th>
                            <td colspan="3">{{$arrestWarrant['descricaoCumprimento']}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>
