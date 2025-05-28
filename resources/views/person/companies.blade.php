<div class="card">
    <div class="card-header text-info">Empresas</div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_company_name"
                    id="new_company_name"
                    label="Empresa"
                    placeholder="Nome da Empresa"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_fantasy_name"
                    id="new_fantasy_name"
                    label="Nome de Fantasia"
                    placeholder="Nome de Fantasia"
                />
            </div>
             <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_cnpj"
                    id="new_cnpj"
                    label="CNPJ"
                    placeholder="CNPJ"
                    class="mask-cnpj"
                    maxlength="14"
                />
            </div>
             <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_phone"
                    id="new_phone"
                    label="Telefone"
                    placeholder="Telefone"
                    class="mask-phone"
                    maxlength="11"
                />
            </div>
             <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_social_capital"
                    id="new_social_capital"
                    label="Capital Social"
                    placeholder="Capital Social"
                    class="mask-money"
                    maxlength="18"
                />
            </div>
            <div class="form-group col-md-2">
                 <x-adminlte-select
                     name="new_status"
                     id="new_status"
                     label="Situação"
                     placeholder="Situação"
                 >
                     <option value="Ativo">Ativo</option>
                     <option value="Inativo">Inativo</option>
                 </x-adminlte-select>
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_cep"
                    id="new_cep"
                    label="CEP"
                    placeholder="CEP"
                    class="mask-cep"
                    maxlength="9"
                />
            </div>
             <div class="form-group col-md-4">
                <x-adminlte-input
                    name="new_address"
                    id="new_address"
                    label="Endereço"
                    placeholder="Endereço"
                />
            </div>
             <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_number"
                    id="new_number"
                    label="Número"
                    placeholder="Número"
                    maxlength="6"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_district"
                    id="new_district"
                    label="Bairro"
                    placeholder="Bairro"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_city"
                    id="new_city"
                    label="Cidade"
                    placeholder="Cidade"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-select
                    name="new_uf"
                    id="new_uf"
                    label="Estado"
                    placeholder="UF"
                >
                    <option value="">Selecione</option>
                    <option value="AC">AC</option>
                    <option value="AL">AL</option>
                    <option value="AP">AP</option>
                    <option value="AM">AM</option>
                    <option value="BA">BA</option>
                    <option value="CE">CE</option>
                    <option value="DF">DF</option>
                    <option value="ES">ES</option>
                    <option value="GO">GO</option>
                    <option value="MA">MA</option>
                    <option value="MT">MT</option>
                    <option value="MS">MS</option>
                    <option value="MG">MG</option>
                    <option value="PA">PA</option>
                    <option value="PB">PB</option>
                    <option value="PR">PR</option>
                    <option value="PE">PE</option>
                    <option value="PI">PI</option>
                    <option value="RJ">RJ</option>
                    <option value="RN">RN</option>
                    <option value="RS">RS</option>
                    <option value="RO">RO</option>
                    <option value="RR">RR</option>
                    <option value="SC">SC</option>
                    <option value="SP">SP</option>
                    <option value="SE">SE</option>
                    <option value="TO">TO</option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_cnae"
                    id="new_cnae"
                    label="CNAE"
                    placeholder="CNAE"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_accountant"
                    id="new_accountant"
                    label="Contador"
                    placeholder="Contador"
                />
            </div>
             <div class="form-group col-md-1 d-flex align-items-center justify-content-center">
                <x-adminlte-button
                    type="button"
                    icon="fas fa-plus"
                    theme="secondary"
                    label="Adicionar"
                    onclick="addCompany()"
                />
            </div>
        </div>
    </div>
</div>

@include('person.companiesList')

@push('js')
<script>
    $(document).ready(function() {
        var companyIndex = {{ isset($person) && $person->companies->count() > 0 ? $person->companies->count() : 0 }};

        $('#add-company').click(function() {
            var companyHtml = '<div class="form-row company-item">' +
                '<div class="form-group col-md-11">' +
                '<label for="companies[' + companyIndex + '][company_name]">Empresa</label>' +
                '<input type="text" name="companies[' + companyIndex + '][company_name]" id="companies[' + companyIndex + '][company_name]" class="form-control" placeholder="Nome da Empresa">' +
                '</div>' +
                '<div class="form-group col-md-1 d-flex align-items-center justify-content-center">' +
                '<button type="button" class="btn btn-danger remove-company">Remover</button>' +
                '</div>' +
                '</div>';
            $('#companies-container').append(companyHtml);
            companyIndex++;
        });

        $('#companies-container').on('click', '.remove-company', function() {
            $(this).closest('.company-item').remove();
        });
    });
</script>
@endpush 