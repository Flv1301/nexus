<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Empresa</th>
                <th>Nome de Fantasia</th>
                <th>CNPJ</th>
                <th>Telefone</th>
                <th>Capital Social</th>
                <th>Situação</th>
                <th>CEP</th>
                <th>Endereço</th>
                <th>Número</th>
                <th>Bairro</th>
                <th>UF</th>
                <th>Cidade</th>
                <th>CNAE</th>
                <th>Contador</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="tableCompanies">
            @foreach($person->companies as $company)
                <tr>
                    <td>{{$company->company_name}}</td>
                    <td>{{$company->fantasy_name}}</td>
                    <td>{{$company->cnpj}}</td>
                    <td>{{$company->phone}}</td>
                    <td>{{$company->social_capital}}</td>
                    <td>{{$company->status}}</td>
                    <td>{{$company->cep}}</td>
                    <td>{{$company->address}}</td>
                    <td>{{$company->number}}</td>
                    <td>{{$company->district}}</td>
                    <td>{{$company->uf}}</td>
                    <td>{{$company->city}}</td>
                    <td>{{$company->cnae}}</td>
                    <td>{{$company->accountant}}</td>
                    <td>
                        <i class="fa fa-md fa-fw fa-trash text-danger"
                           onclick="$(this).parent().parent().remove()"
                           title="Remover"></i>
                        <input type="hidden" name="companies[{{$loop->index}}][id]" value="{{ $company->id ?? '' }}">
                        <input type="hidden" name="companies[{{$loop->index}}][company_name]" value="{{ $company->company_name ?? '' }}">
                        <input type="hidden" name="companies[{{$loop->index}}][fantasy_name]" value="{{ $company->fantasy_name ?? '' }}">
                        <input type="hidden" name="companies[{{$loop->index}}][cnpj]" value="{{ $company->cnpj ?? '' }}">
                        <input type="hidden" name="companies[{{$loop->index}}][phone]" value="{{ $company->phone ?? '' }}">
                        <input type="hidden" name="companies[{{$loop->index}}][social_capital]" value="{{ $company->social_capital ?? '' }}">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('js')
<script>
    function addCompany() {
        const companyNameInput = document.getElementById('new_company_name');
        const fantasyNameInput = document.getElementById('new_fantasy_name');
        const cnpjInput = document.getElementById('new_cnpj');
        const phoneInput = document.getElementById('new_phone');
        const socialCapitalInput = document.getElementById('new_social_capital');
        const statusInput = document.getElementById('new_status');
        const cepInput = document.getElementById('new_cep');
        const addressInput = document.getElementById('new_address');
        const numberInput = document.getElementById('new_number');
        const districtInput = document.getElementById('new_district');
        const cityInput = document.getElementById('new_city');
        const ufInput = document.getElementById('new_uf');
        const cnaeInput = document.getElementById('new_cnae');
        const accountantInput = document.getElementById('new_accountant');

        const companyName = companyNameInput.value.trim();
        const fantasyName = fantasyNameInput.value.trim();
        const cnpj = cnpjInput.value.trim();
        const phone = phoneInput.value.trim();
        const socialCapital = socialCapitalInput.value.trim();
        const status = statusInput.value;
        const cep = cepInput.value.trim();
        const address = addressInput.value.trim();
        const number = numberInput.value.trim();
        const district = districtInput.value.trim();
        const city = cityInput.value.trim();
        const uf = ufInput.value;
        const cnae = cnaeInput.value.trim();
        const accountant = accountantInput.value.trim();

        if (companyName === '') {
            return;
        }

        const tableBody = document.getElementById('tableCompanies');
        const newRow = tableBody.insertRow();

        const nameCell = newRow.insertCell();
        nameCell.textContent = companyName;

        const fantasyNameCell = newRow.insertCell();
        fantasyNameCell.textContent = fantasyName;

        const cnpjCell = newRow.insertCell();
        cnpjCell.textContent = cnpj;

        const phoneCell = newRow.insertCell();
        phoneCell.textContent = phone;

        const socialCapitalCell = newRow.insertCell();
        socialCapitalCell.textContent = socialCapital;

        const statusCell = newRow.insertCell();
        statusCell.textContent = status;

        const cepCell = newRow.insertCell();
        cepCell.textContent = cep;

        const addressCell = newRow.insertCell();
        addressCell.textContent = address;

        const numberCell = newRow.insertCell();
        numberCell.textContent = number;

        const districtCell = newRow.insertCell();
        districtCell.textContent = district;

        const ufCell = newRow.insertCell();
        ufCell.textContent = uf;

        const cityCell = newRow.insertCell();
        cityCell.textContent = city;

        const cnaeCell = newRow.insertCell();
        cnaeCell.textContent = cnae;

        const accountantCell = newRow.insertCell();
        accountantCell.textContent = accountant;

        const actionsCell = newRow.insertCell();
        actionsCell.innerHTML = '<i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i>';

        // Add hidden inputs for the form submission
        const idInput = document.createElement('input');
        idInput.setAttribute('type', 'hidden');
        idInput.setAttribute('name', 'companies[][id]');
        idInput.setAttribute('value', ''); // New companies don't have an ID yet
        newRow.appendChild(idInput);

        const nameInput = document.createElement('input');
        nameInput.setAttribute('type', 'hidden');
        nameInput.setAttribute('name', 'companies[][company_name]');
        nameInput.setAttribute('value', companyName);
        newRow.appendChild(nameInput);

        const fantasyInput = document.createElement('input');
        fantasyInput.setAttribute('type', 'hidden');
        fantasyInput.setAttribute('name', 'companies[][fantasy_name]');
        fantasyInput.setAttribute('value', fantasyName);
        newRow.appendChild(fantasyInput);

        const cnpjInputHidden = document.createElement('input');
        cnpjInputHidden.setAttribute('type', 'hidden');
        cnpjInputHidden.setAttribute('name', 'companies[][cnpj]');
        cnpjInputHidden.setAttribute('value', cnpj);
        cnpjInputHidden.setAttribute('maxlength', '14');
        newRow.appendChild(cnpjInputHidden);

        const phoneInputHidden = document.createElement('input');
        phoneInputHidden.setAttribute('type', 'hidden');
        phoneInputHidden.setAttribute('name', 'companies[][phone]');
        phoneInputHidden.setAttribute('value', phone);
        phoneInputHidden.setAttribute('maxlength', '13');
        newRow.appendChild(phoneInputHidden);

        const socialCapitalInputHidden = document.createElement('input');
        socialCapitalInputHidden.setAttribute('type', 'hidden');
        socialCapitalInputHidden.setAttribute('name', 'companies[][social_capital]');
        socialCapitalInputHidden.setAttribute('value', socialCapital);
        socialCapitalInputHidden.setAttribute('maxlength', '18');
        newRow.appendChild(socialCapitalInputHidden);

        const statusInputHidden = document.createElement('input');
        statusInputHidden.setAttribute('type', 'hidden');
        statusInputHidden.setAttribute('name', 'companies[][status]');
        statusInputHidden.setAttribute('value', status);
        newRow.appendChild(statusInputHidden);

        const cepInputHidden = document.createElement('input');
        cepInputHidden.setAttribute('type', 'hidden');
        cepInputHidden.setAttribute('name', 'companies[][cep]');
        cepInputHidden.setAttribute('value', cep);
        cepInputHidden.setAttribute('maxlength', '9');
        newRow.appendChild(cepInputHidden);

        const addressInputHidden = document.createElement('input');
        addressInputHidden.setAttribute('type', 'hidden');
        addressInputHidden.setAttribute('name', 'companies[][address]');
        addressInputHidden.setAttribute('value', address);
        newRow.appendChild(addressInputHidden);

        const numberInputHidden = document.createElement('input');
        numberInputHidden.setAttribute('type', 'hidden');
        numberInputHidden.setAttribute('name', 'companies[][number]');
        numberInputHidden.setAttribute('value', number);
        numberInputHidden.setAttribute('maxlength', '6');
        newRow.appendChild(numberInputHidden);

        const districtInputHidden = document.createElement('input');
        districtInputHidden.setAttribute('type', 'hidden');
        districtInputHidden.setAttribute('name', 'companies[][district]');
        districtInputHidden.setAttribute('value', district);
        newRow.appendChild(districtInputHidden);

        const cityInputHidden = document.createElement('input');
        cityInputHidden.setAttribute('type', 'hidden');
        cityInputHidden.setAttribute('name', 'companies[][city]');
        cityInputHidden.setAttribute('value', city);
        newRow.appendChild(cityInputHidden);

        const ufInputHidden = document.createElement('input');
        ufInputHidden.setAttribute('type', 'hidden');
        ufInputHidden.setAttribute('name', 'companies[][uf]');
        ufInputHidden.setAttribute('value', uf);
        newRow.appendChild(ufInputHidden);

        const cnaeInputHidden = document.createElement('input');
        cnaeInputHidden.setAttribute('type', 'hidden');
        cnaeInputHidden.setAttribute('name', 'companies[][cnae]');
        cnaeInputHidden.setAttribute('value', cnae);
        newRow.appendChild(cnaeInputHidden);

        const accountantInputHidden = document.createElement('input');
        accountantInputHidden.setAttribute('type', 'hidden');
        accountantInputHidden.setAttribute('name', 'companies[][accountant]');
        accountantInputHidden.setAttribute('value', accountant);
        newRow.appendChild(accountantInputHidden);

        companyNameInput.value = ''; // Clear the input field
        fantasyNameInput.value = '';
        cnpjInput.value = '';
        phoneInput.value = '';
        socialCapitalInput.value = '';
        statusInput.value = 'Ativo'; // Reset status to default
        cepInput.value = '';
        addressInput.value = '';
        numberInput.value = '';
        districtInput.value = '';
        cityInput.innerHTML = '<option value="">Selecione primeiro a UF</option>'; // Reset city select
        ufInput.value = '';
        cnaeInput.value = '';
        accountantInput.value = '';
    }

    // Reindex the hidden inputs before form submission
    $('form').submit(function() {
        $(this).find('#tableCompanies tr').each(function(index) {
            $(this).find('input[name^="companies"]').each(function() {
                const name = $(this).attr('name').replace(/companies\[\d*\]/, 'companies[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });
</script>
@endpush 