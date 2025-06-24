<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Situação</th>
                <th>Processo</th>
                <th>Natureza</th>
                <th>Data</th>
                <th>UF</th>
                <th>Comarca</th>
                <th>Data Denúncia</th>
                <th>Data Condenação</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableTjs">
            @if(isset($person) && $person->tjs->count())
                @foreach($person->tjs as $tj)
                    <tr>
                        <td>{{$tj->situacao}}</td>
                        <td>{{$tj->processo}}</td>
                        <td>{{$tj->natureza}}</td>
                        <td>{{$tj->data}}</td>
                        <td>{{$tj->uf}}</td>
                        <td>{{$tj->comarca}}</td>
                        <td>{{$tj->data_denuncia}}</td>
                        <td>{{$tj->data_condenacao}}</td>
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
        let tjIndex = 0;

        function addTj() {
            const situacao = document.getElementById('tj_situacao').value;
            const dataDenuncia = document.getElementById('tj_data_denuncia').value;
            const dataCondenacao = document.getElementById('tj_data_condenacao').value;
            const processo = document.getElementById('tj_processo').value;
            const natureza = document.getElementById('tj_natureza').value;
            const data = document.getElementById('tj_data').value;
            const uf = document.getElementById('tj_uf').value;
            const comarca = document.getElementById('tj_comarca').value;

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
                situacao: situacao,
                data_denuncia: convertDate(dataDenuncia),
                data_condenacao: convertDate(dataCondenacao),
                processo: processo,
                natureza: natureza,
                data: convertDate(data),
                uf: uf,
                comarca: comarca
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `tjs[${tjIndex}]`);
            input.setAttribute('value', JSON.stringify(tjData));

            // Cria a linha na tabela
            let table = document.querySelector('#tableTjs');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${situacao}</td>
                <td>${processo}</td>
                <td>${natureza}</td>
                <td>${data}</td>
                <td>${uf}</td>
                <td>${comarca}</td>
                <td>${dataDenuncia}</td>
                <td>${dataCondenacao}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('tj_situacao').value = '';
            document.getElementById('tj_data_denuncia').value = '';
            document.getElementById('tj_data_condenacao').value = '';
            document.getElementById('tj_processo').value = '';
            document.getElementById('tj_natureza').value = '';
            document.getElementById('tj_data').value = '';
            document.getElementById('tj_uf').value = '';
            document.getElementById('tj_comarca').value = '';

            // Esconde os campos de data
            document.getElementById('data_denuncia_group').style.display = 'none';
            document.getElementById('data_condenacao_group').style.display = 'none';

            // Incrementa o índice
            tjIndex++;
        }
    </script>
@endpush 