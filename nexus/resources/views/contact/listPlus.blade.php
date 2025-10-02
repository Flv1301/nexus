<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>DDD</th>
                <th>Telefone</th>
                <th>Proprietário</th>
                <th>Operadora</th>
                <th>Data Ativação</th>
                <th>Data Cancelamento</th>
                <th>IMEI</th>
                <th>IMSI</th>
                <th>Modelo de Aparelho</th>
                <th>Data do dado</th>
                <th>Fonte do dado</th>
            </tr>
            </thead>
            <tbody id="tableContacts">
            @if($person->telephones->count())
                @foreach($person->telephones as $telephones)
                    <tr>
                        <td>{{$telephones->ddd}}</td>
                        <td>{{$telephones->telephone}}</td>
                        <td>{{$telephones->owner}}</td>
                        <td>{{$telephones->operator}}</td>
                        <td>{{$telephones->start_link}}</td>
                        <td>{{$telephones->end_link}}</td>
                        <td>{{$telephones->imei}}</td>
                        <td>{{$telephones->imsi}}</td>
                        <td>{{$telephones->device}}</td>
                        <td>{{$telephones->data_do_dado ? date('d/m/Y', strtotime($telephones->data_do_dado)) : ''}}</td>
                        <td>{{$telephones->fonte_do_dado}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" id="contacts_{{ $loop->index }}" name="contacts[]" value="{{json_encode($telephones)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
@push('js')
    <script>
        function addContacts() {
            const inputs = ['ddd', 'telephone', 'operator', 'owner', 'start_link', 'end_link', 'imei', 'imsi', 'device'];
            const values = inputs.reduce((acc, input) => {
                acc[input] = document.querySelector(`#${input}`).value;
                return acc;
            }, {});
            
            // Adicionar os campos com IDs específicos
            values.data_do_dado = document.querySelector('#contact_data_do_dado').value;
            values.fonte_do_dado = document.querySelector('#contact_fonte_do_dado').value;
            
            // Debug para verificar se os campos estão sendo coletados
            console.log('Valores coletados:', values);
            console.log('Data do dado:', values.data_do_dado);
            console.log('Fonte do dado:', values.fonte_do_dado);

            if (values.telephone === '' && values.imei === '' && values.imsi === '' && values.device === '') {
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

            // Converte as datas para o formato correto
            values.start_link = convertDate(values.start_link);
            values.end_link = convertDate(values.end_link);
            values.data_do_dado = convertDate(values.data_do_dado);

            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('id', 'contacts_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9));
            input.setAttribute('name', 'contacts[]');
            input.setAttribute('value', JSON.stringify(values));

            let table = document.querySelector('#tableContacts');
            let tr = document.createElement('tr');
            
            // Para exibição na tabela, mantemos o formato original das datas
            const displayValues = {...values};
            displayValues.start_link = document.querySelector('#start_link').value;
            displayValues.end_link = document.querySelector('#end_link').value;
            displayValues.data_do_dado = document.querySelector('#contact_data_do_dado').value;
            
            tr.innerHTML = inputs.map(input => `<td>${displayValues[input]}</td>`).join('') + `<td>${displayValues.data_do_dado}</td><td>${displayValues.fonte_do_dado}</td><td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>`;
            tr.appendChild(input);
            table.appendChild(tr);

            inputs.forEach(input => {
                document.getElementById(input).value = '';
            });
            
            // Limpar os campos específicos
            document.getElementById('contact_data_do_dado').value = '';
            document.getElementById('contact_fonte_do_dado').value = '';
        }
    </script>
@endpush
