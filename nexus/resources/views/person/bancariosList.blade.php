<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Banco</th>
                <th>Conta</th>
                <th>Agência</th>
                <th>Data de Criação</th>
                <th>Data de Exclusão</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableBancarios">
            @if(isset($person) && $person->bancarios->count())
                @foreach($person->bancarios as $bancario)
                    <tr>
                        <td>{{$bancario->banco}}</td>
                        <td>{{$bancario->conta}}</td>
                        <td>{{$bancario->agencia}}</td>
                        <td>{{$bancario->data_criacao}}</td>
                        <td>{{$bancario->data_exclusao}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" name="bancarios[]" value="{{json_encode($bancario)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        let bancarioIndex = {{ isset($person) && $person->bancarios ? $person->bancarios->count() : 0 }};

        function addBancario() {
            const banco = document.getElementById('bancario_banco').value;
            const conta = document.getElementById('bancario_conta').value;
            const agencia = document.getElementById('bancario_agencia').value;
            const dataCriacao = document.getElementById('bancario_data_criacao').value;
            const dataExclusao = document.getElementById('bancario_data_exclusao').value;

            if (banco.trim() === '') {
                alert('O campo Banco é obrigatório.');
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

            // Cria o objeto do Bancário
            const bancarioData = {
                banco: banco,
                conta: conta,
                agencia: agencia,
                data_criacao: convertDate(dataCriacao),
                data_exclusao: convertDate(dataExclusao)
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `bancarios[${bancarioIndex}]`);
            input.setAttribute('value', JSON.stringify(bancarioData));

            // Cria a linha na tabela
            let table = document.querySelector('#tableBancarios');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${banco}</td>
                <td>${conta}</td>
                <td>${agencia}</td>
                <td>${dataCriacao}</td>
                <td>${dataExclusao}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('bancario_banco').value = '';
            document.getElementById('bancario_conta').value = '';
            document.getElementById('bancario_agencia').value = '';
            document.getElementById('bancario_data_criacao').value = '';
            document.getElementById('bancario_data_exclusao').value = '';

            // Incrementa o índice
            bancarioIndex++;
        }
    </script>
@endpush 