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
                <th>Estado</th>
                <th>UF</th>
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
                    <td>{{$address->state}}</td>
                    <td>{{$address->uf}}</td>
                    <td>{{$address->uf}}</td>
                    <td><i class="fa fa-md fa-fw fa-trash text-danger"
                           onclick="$(this).parent().parent().remove()"
                           title="Remover"></i></td>
                    <input type="hidden" id="addresses" name="addresses[]" value="{{json_encode($address)}}">
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
                reference_point: reference_point
            }
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('id', 'addresses');
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
            td.append(state);
            tr.append(td);
            td = document.createElement('td');
            td.append(uf);
            tr.append(td);
            td = document.createElement('td');
            td.innerHTML = html;
            tr.append(td);
            tr.append(input);
            table.append(tr);
        }
    </script>
@endpush
