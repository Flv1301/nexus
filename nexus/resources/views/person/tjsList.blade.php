@php
    use App\Helpers\StateFlagHelper;
@endphp

<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Processo</th>
                <th>Instância</th>
                <th>Classe</th>
                <th>Assunto</th>
                <th>Autor</th>
                <th>Recebido em</th>
                <th>UF</th>
                <th>Jurisdição</th>
                <th>Processo Prevento</th>
                <th>Situação</th>
                <th>Distribuição</th>
                <th>Órgão Julgador</th>
                <th>Órgão Julgador Colegiado</th>
                <th>Competência</th>
                <th>Nº Inquérito Policial</th>
                <th>Valor da Causa</th>
                <th>Advogado</th>
                <th>Prioridade</th>
                <th>Gratuidade</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableTjs">
            @if(isset($person) && $person->tjs->count())
                @foreach($person->tjs as $tj)
                    <tr>
                        <td>
                            @if($tj->uf)
                                {!! StateFlagHelper::getFlagHtml($tj->uf, StateFlagHelper::getStateName($tj->uf)) !!}&nbsp;
                            @endif
                            {{$tj->processo}}
                        </td>
                        <td>{{$tj->comarca}}</td>
                        <td>{{$tj->classe ?? ''}}</td>
                        <td>{{$tj->natureza}}</td>
                        <td>{{$tj->autor ?? ''}}</td>
                        <td>{{$tj->data}}</td>
                        <td>{{$tj->uf}}</td>
                        <td>{{$tj->jurisdicao ?? ''}}</td>
                        <td>{{$tj->processo_prevento ?? ''}}</td>
                        <td>{{$tj->situacao_processo ?? ''}}</td>
                        <td>{{$tj->distribuicao ?? ''}}</td>
                        <td>{{$tj->orgao_julgador ?? ''}}</td>
                        <td>{{$tj->orgao_julgador_colegiado ?? ''}}</td>
                        <td>{{$tj->competencia ?? ''}}</td>
                        <td>{{$tj->numero_inquerito_policial ?? ''}}</td>
                        <td>{{$tj->valor_causa ? 'R$ ' . number_format($tj->valor_causa, 2, ',', '.') : ''}}</td>
                        <td>{{$tj->advogado ?? ''}}</td>
                        <td>{{$tj->prioridade === true ? 'Sim' : ($tj->prioridade === false ? 'Não' : '')}}</td>
                        <td>{{$tj->gratuidade === true ? 'Sim' : ($tj->gratuidade === false ? 'Não' : '')}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" name="tjs[]" value="{{json_encode($tj)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        let tjIndex = {{ isset($person) && $person->tjs ? $person->tjs->count() : 0 }};

        function addTj() {
            const processo = document.getElementById('tj_processo').value;
            const natureza = document.getElementById('tj_natureza').value;
            const classe = document.getElementById('tj_classe').value;
            const autor = document.getElementById('tj_autor').value;
            const data = document.getElementById('tj_data').value;
            const uf = document.getElementById('tj_uf').value;
            const comarca = document.getElementById('tj_comarca').value;
            // Novos campos
            const jurisdicao = document.getElementById('tj_jurisdicao').value;
            const processoProvento = document.getElementById('tj_processo_prevento').value;
            const situacaoProcesso = document.getElementById('tj_situacao_processo').value;
            const distribuicao = document.getElementById('tj_distribuicao').value;
            const orgaoJulgador = document.getElementById('tj_orgao_julgador').value;
            const orgaoJulgadorColegiado = document.getElementById('tj_orgao_julgador_colegiado').value;
            const competencia = document.getElementById('tj_competencia').value;
            const numeroInqueritorPolicial = document.getElementById('tj_numero_inquerito_policial').value;
            const valorCausa = document.getElementById('tj_valor_causa').value;
            const advogado = document.getElementById('tj_advogado').value;
            const prioridade = document.getElementById('tj_prioridade').value;
            const gratuidade = document.getElementById('tj_gratuidade').value;

            if (processo.trim() === '') {
                alert('O campo Processo é obrigatório.');
                return;
            }

            // Função para converter data do formato DD/MM/YYYY para YYYY-MM-DD
            function convertDate(dateStr) {
                if (!dateStr || dateStr.trim() === '') return '';
                
                // Verifica se está no formato DD/MM/YYYY
                const dateParts = dateStr.split('/');
                if (dateParts.length === 3) {
                    const [day, month, year] = dateParts;
                    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                }
                return dateStr; // Retorna como está se não for no formato esperado
            }

            // Cria o objeto do TJ
            const tjData = {
                processo: processo,
                natureza: natureza,
                classe: classe,
                autor: autor,
                data: convertDate(data),
                uf: uf,
                comarca: comarca,
                jurisdicao: jurisdicao,
                processo_prevento: processoProvento,
                situacao_processo: situacaoProcesso,
                distribuicao: distribuicao,
                orgao_julgador: orgaoJulgador,
                orgao_julgador_colegiado: orgaoJulgadorColegiado,
                competencia: competencia,
                numero_inquerito_policial: numeroInqueritorPolicial,
                valor_causa: valorCausa,
                advogado: advogado,
                prioridade: prioridade,
                gratuidade: gratuidade
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `tjs[${tjIndex}]`);
            input.setAttribute('value', JSON.stringify(tjData));

            // Função para gerar HTML da bandeira
            function getFlagHtml(uf) {
                if (!uf || uf.trim() === '') return '';
                
                const flagMap = {
                    'AC': 'ac.png',
                    'AL': 'al.png',
                    'AP': 'ap.png',
                    'AM': 'am.png',
                    'BA': 'ba.png',
                    'CE': 'ce.png',
                    'DF': 'df.png',
                    'ES': 'es.png',
                    'GO': 'go.png',
                    'MA': 'ma.png',
                    'MT': 'mt.png',
                    'MS': 'ms.png',
                    'MG': 'mg.png',
                    'PA': 'pa.png',
                    'PB': 'pb.png',
                    'PR': 'pr.png',
                    'PE': 'pe.png',
                    'PI': 'pi.png',
                    'RJ': 'rj.png',
                    'RN': 'rn.png',
                    'RS': 'rs.png',
                    'RO': 'ro.png',
                    'RR': 'rr.png',
                    'SC': 'sc.png',
                    'SP': 'sp.png',
                    'SE': 'se.png',
                    'TO': 'to.png'
                };
                
                const flagFile = flagMap[uf.toUpperCase()];
                if (flagFile) {
                    return `<img src="/images/flags/${flagFile}" alt="Bandeira ${uf}" title="${uf}" class="state-flag" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';"><span class="state-flag-fallback" style="display:none;" title="${uf}">${uf}</span>`;
                }
                return `<span class="state-flag-fallback" title="${uf}">${uf}</span>`;
            }

            // Cria a linha na tabela
            let table = document.querySelector('#tableTjs');
            let tr = document.createElement('tr');
            
            // Função para formatar valor da causa
            function formatCurrency(value) {
                if (!value || value.trim() === '') return '';
                const numValue = parseFloat(value);
                if (isNaN(numValue)) return '';
                return 'R$ ' + numValue.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            
            // Função para formatar sim/não
            function formatSimNao(value) {
                if (value === '1') return 'Sim';
                if (value === '0') return 'Não';
                return '';
            }
            
            tr.innerHTML = `
                <td>${getFlagHtml(uf)}&nbsp;${processo}</td>
                <td>${comarca}</td>
                <td>${classe}</td>
                <td>${natureza}</td>
                <td>${autor}</td>
                <td>${data}</td>
                <td>${uf}</td>
                <td>${jurisdicao}</td>
                <td>${processoProvento}</td>
                <td>${situacaoProcesso}</td>
                <td>${distribuicao}</td>
                <td>${orgaoJulgador}</td>
                <td>${orgaoJulgadorColegiado}</td>
                <td>${competencia}</td>
                <td>${numeroInqueritorPolicial}</td>
                <td>${formatCurrency(valorCausa)}</td>
                <td>${advogado}</td>
                <td>${formatSimNao(prioridade)}</td>
                <td>${formatSimNao(gratuidade)}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('tj_processo').value = '';
            document.getElementById('tj_natureza').value = '';
            document.getElementById('tj_classe').value = '';
            document.getElementById('tj_autor').value = '';
            document.getElementById('tj_data').value = '';
            document.getElementById('tj_uf').value = '';
            document.getElementById('tj_comarca').value = '';
            // Novos campos
            document.getElementById('tj_jurisdicao').value = '';
            document.getElementById('tj_processo_prevento').value = '';
            document.getElementById('tj_situacao_processo').value = '';
            document.getElementById('tj_distribuicao').value = '';
            document.getElementById('tj_orgao_julgador').value = '';
            document.getElementById('tj_orgao_julgador_colegiado').value = '';
            document.getElementById('tj_competencia').value = '';
            document.getElementById('tj_numero_inquerito_policial').value = '';
            document.getElementById('tj_valor_causa').value = '';
            document.getElementById('tj_advogado').value = '';
            document.getElementById('tj_prioridade').value = '';
            document.getElementById('tj_gratuidade').value = '';

            // Incrementa o índice
            tjIndex++;
        }
    </script>
@endpush 