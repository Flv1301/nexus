{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 18/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@section('plugins.TempusDominusBs4', true)
<div class="overlay-wrapper">
    <x-loading/>
    <div class="form-row">
        <div class="form-group col-md-2">
            <x-adminlte-input
                name="code"
                label="CEP"
                placeholder="CEP"
                class="mask-code"
                value="{{ old('code') ?? $address->code ?? ''}}"
            />
        </div>
        <div class="form-group d-flex align-items-center mt-3 mr-3">
            <x-adminlte-button
                type="button"
                icon="fas fa-search"
                theme="info"
                id="address-button"
                label="Consultar"
                onclick="zipCode()"
            />
        </div>
        <div class="form-group col-md-6">
            <x-adminlte-input
                name="address"
                id="address"
                placeholder="Logradouro"
                label="Logradouro"
                style="text-transform:uppercase"
                value="{{ old('address') ?? $address->address ?? '' }}"
            />
        </div>
        <div class="form-group col-md-2">
            <x-adminlte-input
                name="number"
                id="number"
                label="Número"
                placeholder="Número"
                value="{{ old('number') ?? $address->number ?? ''}}"
            />
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-4">
            <x-adminlte-input
                name="district"
                id="district"
                label="Bairro"
                placeholder="Bairro"
                style="text-transform:uppercase"
                value="{{ old('district') ?? $address->district ?? ''}}"
            />
        </div>
        <div class="form-group col-md-4">
            <x-adminlte-input
                name="city"
                id="city"
                label="Cidade"
                placeholder="Cidade"
                style="text-transform:uppercase"
                value="{{ old('city') ?? $address->city ?? ''}}"
            />
        </div>
        <div class="form-group col-md-3">
            <x-adminlte-select
                name="state"
                id="state"
                label="Estado"
                placeholder="Estado"
            >
                <option/>
                @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                    <option
                        {{$uf->state() == old('state') ? 'selected' : ($address->state == $uf->state() ? 'selected' : '')}}>
                        {{$uf->state()}}
                    </option>
                @endforeach
            </x-adminlte-select>
        </div>
        <div class="form-group col-md-1">
            <x-adminlte-select
                name="uf"
                id="uf"
                label="UF"
                placeholder="UF"
                style="text-transform:uppercase">
                <option/>
                @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                    <option {{$uf->name == old('uf') ?  'selected' : ($address->uf == $uf->name ? 'selected' : '')}} >
                        {{ $uf->name }}
                    </option>
                @endforeach
            </x-adminlte-select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <x-adminlte-input
                name="complement"
                id="complement"
                label="Complemento"
                placeholder="Complemento"
                value="{{ old('complement') ?? $address->complement ?? ''}}"
            />
        </div>
        <div class="form-group col-md-6">
            <x-adminlte-input
                name="reference_point"
                id="reference_point"
                label="Ponto de referência"
                placeholder="Ponto de referência"
                value="{{ old('reference_point') ?? $address->reference_point ?? ''}}"
            />
        </div>
    </div>
</div>
@push('js')
    <script>
        async function zipCode() {
            let loading = document.getElementById('loading');
            loading.classList.remove('d-none');
            let code = document.getElementById('code').value;
            if (code.length === 8) {
                const url = "{{url('zipcode')}}/" + code;
                let response = await fetch(url);
                let data = await response.json();
                if (!response.ok || data.error || data.length === 0) {
                    loading.classList.add('d-none');
                    return;
                }
                console.log(data.length);
                document.getElementById('address').value = data.address;
                document.getElementById('district').value = data.district;
                document.getElementById('city').value = data.city;
                document.getElementById('uf').value = data.uf;
                document.getElementById('state').value = data.state;
            }
            loading.classList.add('d-none');
        }
    </script>
@endpush
