@php
    use App\Helpers\StateFlagHelper;
@endphp

<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>BO</th>
                <th>Natureza</th>
                <th>Data</th>
                <th>UF</th>
                <th>Cidade</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tablePcpas">
            @if(isset($person) && $person->pcpas->count())
                @foreach($person->pcpas as $pcpa)
                    <tr>
                        <td>
                            @if($pcpa->uf)
                                {!! StateFlagHelper::getFlagHtml($pcpa->uf, StateFlagHelper::getStateName($pcpa->uf)) !!}&nbsp;
                            @endif
                            {{$pcpa->bo}}
                        </td>
                        <td>{{$pcpa->natureza}}</td>
                        <td>{{$pcpa->data}}</td>
                        <td>{{$pcpa->uf}}</td>
                        <td>{{$pcpa->cidade}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" name="pcpas[]" value="{{json_encode($pcpa)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        let pcpaIndex = {{ isset($person) && $person->pcpas ? $person->pcpas->count() : 0 }};

        function addPcpa() {
            const bo = document.getElementById('pcpa_bo').value;
            const natureza = document.getElementById('pcpa_natureza').value;
            const data = document.getElementById('pcpa_data').value;
            const uf = document.getElementById('pcpa_uf').value;
            const cidade = document.getElementById('pcpa_cidade').value;

            if (bo.trim() === '') {
                alert('O campo BO é obrigatório.');
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

            // Cria o objeto do PCPA
            const pcpaData = {
                bo: bo,
                natureza: natureza,
                data: convertDate(data),
                uf: uf,
                cidade: cidade
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `pcpas[${pcpaIndex}]`);
            input.setAttribute('value', JSON.stringify(pcpaData));

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
            let table = document.querySelector('#tablePcpas');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${getFlagHtml(uf)}&nbsp;${bo}</td>
                <td>${natureza}</td>
                <td>${data}</td>
                <td>${uf}</td>
                <td>${cidade}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('pcpa_bo').value = '';
            document.getElementById('pcpa_natureza').value = '';
            document.getElementById('pcpa_data').value = '';
            document.getElementById('pcpa_uf').value = '';
            document.getElementById('pcpa_cidade').value = '';
            document.getElementById('pcpa_cidade').innerHTML = '<option value="">Selecione primeiro a UF</option>';

            // Incrementa o índice
            pcpaIndex++;
        }
    </script>
@endpush 