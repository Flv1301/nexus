<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Empresa/Orgão</th>
                <th>CNPJ</th>
                <th>Tipo de Vínculo</th>
                <th>Admissão</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableRais">
            @if(isset($person) && $person->rais->count())
                @foreach($person->rais as $rais)
                    <tr>
                        <td>{{$rais->empresa_orgao}}</td>
                        <td>{{$rais->cnpj}}</td>
                        <td>{{$rais->tipo_vinculo}}</td>
                        <td>{{$rais->admissao}}</td>
                        <td>{{$rais->situacao}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" name="rais[]" value="{{json_encode($rais)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        let raisIndex = {{ isset($person) && $person->rais ? $person->rais->count() : 0 }};

        function addRais() {
            const empresaOrgao = document.getElementById('rais_empresa_orgao').value;
            const cnpj = document.getElementById('rais_cnpj').value;
            const tipoVinculo = document.getElementById('rais_tipo_vinculo').value;
            const admissao = document.getElementById('rais_admissao').value;
            const situacao = document.getElementById('rais_situacao').value;

            if (empresaOrgao.trim() === '') {
                alert('O campo Empresa/Orgão é obrigatório.');
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

            // Cria o objeto do RAIS
            const raisData = {
                empresa_orgao: empresaOrgao,
                cnpj: cnpj,
                tipo_vinculo: tipoVinculo,
                admissao: convertDate(admissao),
                situacao: situacao
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `rais[${raisIndex}]`);
            input.setAttribute('value', JSON.stringify(raisData));

            // Cria a linha na tabela
            let table = document.querySelector('#tableRais');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${empresaOrgao}</td>
                <td>${cnpj}</td>
                <td>${tipoVinculo}</td>
                <td>${admissao}</td>
                <td>${situacao}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('rais_empresa_orgao').value = '';
            document.getElementById('rais_cnpj').value = '';
            document.getElementById('rais_tipo_vinculo').value = '';
            document.getElementById('rais_admissao').value = '';
            document.getElementById('rais_situacao').value = '';

            // Incrementa o índice
            raisIndex++;
        }
    </script>
@endpush 