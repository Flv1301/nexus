@section('plugins.TempusDominusBs4', true)
<div class="overlay-wrapper">
    <x-loading/>
    <div class="form-row">
        <div class="form-group col-md-2">
            <x-adminlte-input
                name="code"
                id="code"
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
        <div class="form-group col-md-3">
            <x-adminlte-select
                name="uf"
                id="uf"
                label="UF"
                placeholder="UF"
                style="text-transform:uppercase"
                onchange="loadCitiesByUF('uf', 'city')"
            >
                <option/>
                @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                    <option value="{{$uf->name}}" {{$uf->name == old('uf') ?  'selected' : ($address->uf == $uf->name ? 'selected' : '')}} >
                        {{ $uf->name }}
                    </option>
                @endforeach
            </x-adminlte-select>
        </div>
        <div class="form-group col-md-5">
            <x-adminlte-select
                name="city"
                id="city"
                label="Cidade"
                placeholder="Selecione primeiro a UF"
            >
                <option value="">Selecione primeiro a UF</option>
                @if(old('city') || $address->city)
                    <option value="{{ old('city') ?? $address->city ?? '' }}" selected>
                        {{ old('city') ?? $address->city ?? '' }}
                    </option>
                @endif
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
    <div class="form-row">
        <div class="form-group col-md-12">
            <x-adminlte-textarea
                name="observacao"
                id="observacao"
                label="Observação"
                placeholder="Observação"
                rows="3"
            >{{ old('observacao') ?? $address->observacao ?? '' }}</x-adminlte-textarea>
        </div>
    </div>
    <div class="form-row">
        @php
            $config = ['format' => 'DD/MM/YYYY'];
        @endphp
        <div class="form-group col-md-3">
            <x-adminlte-input-date 
                name="data_do_dado"
                id="data_do_dado"
                :config="$config"
                placeholder="Data do dado"
                label="Data do dado"
                value="{{ old('data_do_dado') ?? $address->data_do_dado ?? '' }}">
                <x-slot name="appendSlot">
                    <div class="input-group-text bg-gradient-warning">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </x-slot>
            </x-adminlte-input-date>
        </div>
        <div class="form-group col-md-3">
            <x-adminlte-input
                name="fonte_do_dado"
                id="fonte_do_dado"
                label="Fonte do dado"
                placeholder="Fonte do dado"
                value="{{ old('fonte_do_dado') ?? $address->fonte_do_dado ?? ''}}"
            />
        </div>
    </div>
    
    <!-- Campo hidden para manter compatibilidade com backend -->
    <input type="hidden" name="state" id="state" value="{{ old('state') ?? $address->state ?? '' }}">
</div>
@push('js')
    <script>
        async function zipCode() {
            let loading = document.getElementById('loading');
            if (!loading) {
                console.error('Elemento loading não encontrado');
                alert('Erro: Elemento loading não encontrado na página.');
                return;
            }
            loading.classList.remove('d-none');
            
            let cepField = document.getElementById('code');
            if (!cepField) {
                console.error('Campo CEP não encontrado');
                alert('Erro: Campo CEP não encontrado na página.');
                loading.classList.add('d-none');
                return;
            }
            
            let code = cepField.value;
            
            // Remove máscara do CEP (remove pontos, hífens e espaços)
            let cleanCode = code.replace(/\D/g, '');
            
            console.log('CEP digitado:', code);
            console.log('CEP limpo:', cleanCode);
            
            if (cleanCode.length !== 8) {
                alert('Por favor, digite um CEP válido com 8 dígitos.');
                loading.classList.add('d-none');
                return;
            }
            
            try {
                const url = "{{url('zipcode')}}/" + cleanCode;
                console.log('Consultando URL:', url);
                
                let response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                
                let data = await response.json();
                console.log('Dados retornados:', data);
                
                if (!data || Object.keys(data).length === 0 || data.error) {
                    alert('CEP não encontrado. Verifique se o CEP está correto.');
                    loading.classList.add('d-none');
                    return;
                }
                
                // Preencher os campos com os dados retornados
                if (data.address) {
                    document.getElementById('address').value = data.address;
                }
                if (data.district) {
                    document.getElementById('district').value = data.district;
                }
                if (data.uf) {
                    document.getElementById('uf').value = data.uf;
                    document.getElementById('state').value = data.state || '';
                    
                    // Trigger o evento change para carregar as cidades
                    document.getElementById('uf').dispatchEvent(new Event('change'));
                    
                    // Aguardar um pouco para as cidades carregarem, então selecionar a cidade
                    setTimeout(() => {
                        if (data.city) {
                            const citySelect = document.getElementById('city');
                            // Procurar pela opção da cidade e selecioná-la
                            for (let option of citySelect.options) {
                                if (option.value === data.city) {
                                    option.selected = true;
                                    break;
                                }
                            }
                            // Se não encontrou a cidade nas opções, adicionar como nova opção
                            if (citySelect.value !== data.city) {
                                const newOption = document.createElement('option');
                                newOption.value = data.city;
                                newOption.textContent = data.city;
                                newOption.selected = true;
                                citySelect.appendChild(newOption);
                            }
                        }
                    }, 1000);
                }
                
                // Mostrar mensagem de sucesso
                if (typeof toastr !== 'undefined') {
                    toastr.success('CEP consultado com sucesso!');
                } else {
                    console.log('CEP consultado com sucesso!');
                }
                
            } catch (error) {
                console.error('Erro ao consultar CEP:', error);
                alert('Erro ao consultar CEP. Verifique sua conexão e tente novamente.');
            } finally {
                loading.classList.add('d-none');
            }
        }
    </script>
@endpush
