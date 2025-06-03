<div class="form-row">
    <div class="form-group col-2">
        <x-adminlte-input
            name="code"
            label="CEP"
            placeholder="CEP"
            disabled
            value="{{ $address->code ?? ''}}"
        />
    </div>
    <div class="form-group col-8">
        <x-adminlte-input
            name="address"
            placeholder="Logradouro"
            label="Logradouro"
            disabled
            value="{{ $address->address ?? ''}}"
        />
    </div>
    <div class="form-group col-2">
        <x-adminlte-input
            name="number"
            label="Número"
            placeholder="Número"
            disabled
            value="{{ $address->number ?? ''}}"
        />
    </div>
</div>
<div class="form-row">
    <div class="form-group col-4">
        <x-adminlte-input
            name="district"
            label="Bairro"
            placeholder="Bairro"
            disabled
            value="{{ $address->district ?? ''}}"
        />
    </div>
    <div class="form-group col-4">
        <x-adminlte-input
            name="city"
            label="Cidade"
            placeholder="Cidade"
            disabled
            value="{{ $address->city ?? ''}}"
        />
    </div>
    <div class="form-group col-4">
        <x-adminlte-input
            name="uf"
            label="UF"
            placeholder="UF"
            disabled
            value="{{ $address->uf ?? ''}}"
        />
    </div>
</div>
<div class="form-row">
    <div class="form-group col-6">
        <x-adminlte-input
            name="complement"
            label="Complemento"
            placeholder="Complemento"
            disabled
            value="{{ $address->complement ?? ''}}"
        />
    </div>
    <div class="form-group col-6">
        <x-adminlte-input
            name="reference_point"
            label="Ponto de referência"
            placeholder="Ponto de referência"
            disabled
            value="{{ $address->reference_point ?? ''}}"
        />
    </div>
</div>
