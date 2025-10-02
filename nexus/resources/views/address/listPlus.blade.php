<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>CEP</th>
                <th>Endereço</th>
                <th>Número</th>
                <th>Bairro</th>
                <th>Cidade</th>
                <th>UF</th>
                <th>Complemento</th>
                <th>Ponto de Referencia</th>
                <th>Observação</th>
                <th>Data do dado</th>
                <th>Fonte do dado</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableAddress">

            @foreach($person->address as $address)
                <tr>
                    <td>{{$address->code}}</td>
                    <td>{{$address->address}}</td>
                    <td>{{$address->number}}</td>
                    <td>{{$address->district}}</td>
                    <td>{{$address->city}}</td>
                    <td>{{$address->uf}}</td>
                    <td>{{$address->complement}}</td>
                    <td>{{$address->reference_point}}</td>
                    <td>{{$address->observacao}}</td>
                    <td>{{$address->data_do_dado ? date('d/m/Y', strtotime($address->data_do_dado)) : ''}}</td>
                    <td>{{$address->fonte_do_dado}}</td>
                    <td>
                        <x-adminlte-button size="sm" theme="warning" icon="fas fa-edit"
                                           onclick="window.location.href='{{ route('address.edit', $address) }}'"/>
                        <x-adminlte-button size="sm" theme="danger" icon="fas fa-trash"
                                           onclick="if(confirm('Deseja realmente excluir?')) window.location.href='{{ route('address.destroy', $address) }}'"/>
                    </td>
                    <input type="hidden" id="addresses_existing_{{ $loop->index }}" name="addresses[]" value="{{ json_encode([
                        'code' => $address->code,
                        'address' => $address->address,
                        'number' => $address->number,
                        'district' => $address->district,
                        'city' => $address->city,
                        'state' => $address->state,
                        'uf' => $address->uf,
                        'complement' => $address->complement,
                        'reference_point' => $address->reference_point,
                        'observacao' => $address->observacao,
                        'data_do_dado' => $address->data_do_dado,
                        'fonte_do_dado' => $address->fonte_do_dado
                    ]) }}">
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@push('js')
    <script>
        function addAddress() {
            const html = `<i class="fa fa-md fa-fw fa-trash text-danger"
                            onclick="$(this).parent().parent().remove()"
                            title="Remover"></i>`;
            let code = document.getElementById('code').value;
            let address = document.getElementById('address').value;
            let number = document.getElementById('number').value;
            let district = document.getElementById('district').value;
            let city = document.getElementById('city').value;
            let state = document.getElementById('state').value;
            let uf = document.getElementById('uf').value;
            let complement = document.getElementById('complement').value;
            let reference_point = document.getElementById('reference_point').value;
            let observacao = document.getElementById('observacao').value;
            let data_do_dado = document.getElementById('data_do_dado').value;
            let fonte_do_dado = document.getElementById('fonte_do_dado').value;
            
            // Converter data de DD/MM/YYYY para YYYY-MM-DD (formato ISO)
            let convertedDate = '';
            if (data_do_dado && data_do_dado.length === 10) {
                const dateParts = data_do_dado.split('/');
                if (dateParts.length === 3) {
                    convertedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];
                }
            }
            
            let addresses = [];
            if (address === '') {
                return;
            }
            addresses = {
                code: code,
                address: address,
                number: number,
                district: district,
                city: city,
                state: state,
                uf: uf,
                complement: complement,
                reference_point: reference_point,
                observacao: observacao,
                data_do_dado: convertedDate,
                fonte_do_dado: fonte_do_dado
            }
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('id', 'addresses_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9));
            input.setAttribute('name', 'addresses[]');
            input.setAttribute('value', JSON.stringify(addresses));
            let table = document.getElementById('tableAddress');
            let tr = document.createElement('tr');
            let td = document.createElement('td');
            td.append(code);
            tr.append(td);
            td = document.createElement('td');
            td.append(address);
            tr.append(td);
            td = document.createElement('td');
            td.append(number);
            tr.append(td);
            td = document.createElement('td');
            td.append(district);
            tr.append(td);
            td = document.createElement('td');
            td.append(city);
            tr.append(td);
            td = document.createElement('td');
            td.append(uf);
            tr.append(td);
            td = document.createElement('td');
            td.append(complement);
            tr.append(td);
            td = document.createElement('td');
            td.append(reference_point);
            tr.append(td);
            td = document.createElement('td');
            td.append(observacao);
            tr.append(td);
            td = document.createElement('td');
            td.append(data_do_dado);
            tr.append(td);
            td = document.createElement('td');
            td.append(fonte_do_dado);
            tr.append(td);
            td = document.createElement('td');
            td.innerHTML = html;
            tr.append(td);
            tr.append(input);
            table.append(tr);
            
            // Limpar os campos após adicionar
            document.getElementById('code').value = '';
            document.getElementById('address').value = '';
            document.getElementById('number').value = '';
            document.getElementById('district').value = '';
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            document.getElementById('uf').value = '';
            document.getElementById('complement').value = '';
            document.getElementById('reference_point').value = '';
            document.getElementById('observacao').value = '';
            document.getElementById('data_do_dado').value = '';
            document.getElementById('fonte_do_dado').value = '';
        }
    </script>
@endpush
