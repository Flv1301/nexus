@php
    $isDisabled = $person->active_orcrim && !auth()->user()->can('sisfac');
@endphp
<div class="card">
    <x-loading/>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="cpf"
                    id="cpf"
                    label="CPF"
                    placeholder="CPF (Somente números.)"
                    maxlength="11"
                    class="mask-cpf-number"
                    value="{{ old('cpf') ?? $person->cpf ?? '' }}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group d-flex align-items-center mt-3 mr-3">
                <x-adminlte-button
                    type="button"
                    icon="fas fa-search"
                    theme="info"
                    id="cpf-button"
                    label="Consultar"
                    onclick="searchCPF()"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-5">
                <x-adminlte-input
                    name="name"
                    id="name"
                    label="Nome completo*"
                    placeholder="Nome completo"
                    style="text-transform:uppercase"
                    value="{{ old('name') ?? $person->name ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="nickname"
                    id="nickname"
                    label="Alcunha"
                    placeholder="Alcunha"
                    style="text-transform:uppercase"
                    value="{{ old('nickname') ?? $person->nickname ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-1">
                <x-adminlte-select
                    name="dead"
                    id="dead"
                    label="Morto"
                    :disabled="$isDisabled"
                >
                    <option value="0" {{ (old('dead') ?? $person->dead ?? false) == '0' || !(old('dead') ?? $person->dead ?? false) ? 'selected' : '' }}>NÃO</option>
                    <option value="1" {{ (old('dead') ?? $person->dead ?? false) == '1' || (old('dead') ?? $person->dead ?? false) === true ? 'selected' : '' }}>SIM</option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-select
                    name="situacao"
                    id="situacao"
                    label="Situação"
                    onchange="toggleSituacaoDateFields()"
                    :disabled="$isDisabled"
                >
                    <option value="">Selecione...</option>
                    <option value="SUSPEITO" {{ (old('situacao') ?? $person->situacao ?? '') == 'SUSPEITO' ? 'selected' : '' }}>SUSPEITO</option>
                    <option value="CAUTELAR" {{ (old('situacao') ?? $person->situacao ?? '') == 'CAUTELAR' ? 'selected' : '' }}>CAUTELAR</option>
                    <option value="DENUNCIADO" {{ (old('situacao') ?? $person->situacao ?? '') == 'DENUNCIADO' ? 'selected' : '' }}>DENUNCIADO</option>
                    <option value="CONDENADO" {{ (old('situacao') ?? $person->situacao ?? '') == 'CONDENADO' ? 'selected' : '' }}>CONDENADO</option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-3" id="data_cautelar_group" style="display: none;">
                @php $config = ['format' => 'DD/MM/YYYY']; @endphp
                <x-adminlte-input-date 
                    name="data_cautelar"
                    id="data_cautelar"
                    :config="$config"
                    placeholder="Data da Cautelar"
                    label="Data da Cautelar"
                    value="{{ old('data_cautelar') ?? $person->data_cautelar ?? '' }}"
                    :disabled="$isDisabled">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3" id="data_denuncia_group" style="display: none;">
                @php $config = ['format' => 'DD/MM/YYYY']; @endphp
                <x-adminlte-input-date 
                    name="data_denuncia"
                    id="data_denuncia"
                    :config="$config"
                    placeholder="Data da Denúncia"
                    label="Data da Denúncia"
                    value="{{ old('data_denuncia') ?? $person->data_denuncia ?? '' }}"
                    :disabled="$isDisabled">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3" id="data_condenacao_group" style="display: none;">
                @php $config = ['format' => 'DD/MM/YYYY']; @endphp
                <x-adminlte-input-date 
                    name="data_condenacao"
                    id="data_condenacao"
                    :config="$config"
                    placeholder="Data da Condenação"
                    label="Data da Condenação"
                    value="{{ old('data_condenacao') ?? $person->data_condenacao ?? '' }}"
                    :disabled="$isDisabled">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-select
                    name="sex"
                    id="sex"
                    label="Sexo"
                    placeholder="Sexo"
                    :disabled="$isDisabled"
                >
                    <option/>
                    <option value="MASCULINO"
                        {{ old('sex') == 'MASCULINO' ? 'selected' : ($person->sex == 'MASCULINO' ? 'selected' : '')}}>
                        MASCULINO
                    </option>
                    <option value="FEMININO"
                        {{ old('sex') == 'FEMININO' ? 'selected' : ($person->sex == 'FEMININO' ? 'selected' : '')}}>
                        FEMININO
                    </option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-3">
                <x-adminlte-input-date name="birth_date"
                                       class="mask-date"
                                       id="birth_date"
                                       :config="$config"
                                       placeholder="Data Nascimento"
                                       label="Data Nascimento"
                                       value="{{old('birth_date') ?? $person->birth_date ?? ''}}"
                                       :disabled="$isDisabled"
                >
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="rg"
                    id="rg"
                    label="RG"
                    placeholder="RG"
                    value="{{ old('rg') ?? $person->rg ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="conselho_de_classe"
                    id="conselho_de_classe"
                    label="Conselho de Classe"
                    placeholder="Informações do Conselho de Classe"
                    value="{{ old('conselho_de_classe') ?? $person->conselho_de_classe ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="voter_registration"
                    id="voter_registration"
                    label="Titulo Eleitor"
                    placeholder="Titulo Eleitor"
                    value="{{ old('voter_registration') ?? $person->voter_registration ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-select
                    name="birth_city"
                    id="birth_city"
                    label="Município Nascimento"
                    placeholder="Selecione primeiro a UF"
                    :disabled="$isDisabled"
                >
                    <option value="">Selecione primeiro a UF</option>
                    @if(old('birth_city') || $person->birth_city)
                        <option value="{{ old('birth_city') ?? $person->birth_city ?? '' }}" selected>
                            {{ old('birth_city') ?? $person->birth_city ?? '' }}
                        </option>
                    @endif
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-1">
                <x-adminlte-select
                    name="uf_birth_city"
                    id="uf_birth_city"
                    label="UF"
                    placeholder="UF"
                    :disabled="$isDisabled"
                    onchange="loadCitiesByUF('uf_birth_city', 'birth_city')"
                >
                    <option/>
                    @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                        <option value="{{$uf->name}}"
                            {{ old('uf_birth_city') == $uf->name ? 'selected'
                            : ($person->uf_birth_city == $uf->name ? 'selected' : '')}}>
                            {{ $uf->name }}
                        </option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="father"
                    id="father"
                    label="Nome do pai"
                    placeholder="Nome do pai"
                    style="text-transform:uppercase"
                    value="{{ old('father') ?? $person->father ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="mother"
                    id="mother"
                    label="Nome da mãe"
                    placeholder="Nome da mãe"
                    style="text-transform:uppercase"
                    value="{{ old('mother') ?? $person->mother ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="spouse_name"
                    id="spouse_name"
                    label="Cônjuge"
                    placeholder="Nome do Cônjuge"
                    style="text-transform:uppercase"
                    value="{{ old('spouse_name') ?? $person->spouse_name ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="spouse_cpf"
                    id="spouse_cpf"
                    label="CPF do Cônjuge"
                    placeholder="CPF do Cônjuge"
                    maxlength="11"
                    class="mask-cpf-number"
                    value="{{ old('spouse_cpf') ?? $person->spouse_cpf ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-12">
                <x-adminlte-input
                    name="tatto"
                    id="tatto"
                    label="Tatuagem"
                    placeholder="Tatuagem"
                    style="text-transform:uppercase"
                    value="{{ old('tatto') ?? $person->tatto ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <x-adminlte-input
                    name="occupation"
                    id="occupation"
                    label="Ocupação/Profissão"
                    placeholder="Ocupação/Profissão"
                    style="text-transform:uppercase"
                    value="{{ old('occupation') ?? $person->occupation ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
        </div>
        @if(!$person->active_orcrim || auth()->user()->can('sisfac'))
            <div class="form-group col-md-12">
                @php
                    $config = [
                        "height" => "100",
                        "toolbar" => [
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['font', ['strikethrough', 'superscript', 'subscript']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']]
                        ],
                    ]
                @endphp
                <x-adminlte-text-editor name="observation" label="Observações"
                                        label-class="text-dark"
                                        placeholder="Observações"
                                        :config="$config">
                    {{ old('observation') ?? $person->observation }}
                </x-adminlte-text-editor>
            </div>
        @endif
    </div>
</div>
@push('js')
    <script>
        // Função para controlar campos condicionais da situação
        function toggleSituacaoDateFields() {
            const situacao = document.getElementById('situacao').value;
            const dataCautelarGroup = document.getElementById('data_cautelar_group');
            const dataDenunciaGroup = document.getElementById('data_denuncia_group');
            const dataCondenacaoGroup = document.getElementById('data_condenacao_group');
            
            // Esconde todos os campos de data primeiro
            dataCautelarGroup.style.display = 'none';
            dataDenunciaGroup.style.display = 'none';
            dataCondenacaoGroup.style.display = 'none';
            
            // Mostra campo baseado na situação
            if (situacao === 'CAUTELAR') {
                dataCautelarGroup.style.display = 'block';
            } else if (situacao === 'DENUNCIADO') {
                dataDenunciaGroup.style.display = 'block';
            } else if (situacao === 'CONDENADO') {
                dataCondenacaoGroup.style.display = 'block';
            }
        }
        
        // Executa quando a página carrega para mostrar campos já preenchidos
        document.addEventListener('DOMContentLoaded', function() {
            toggleSituacaoDateFields();
        });
        
        // Função para remover acentos
        function removeAccents(str) {
            if (!str) return str;
            
            const accentsMap = {
                'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A',
                'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a',
                'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E',
                'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e',
                'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I',
                'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i',
                'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O',
                'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o',
                'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U',
                'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u',
                'Ç': 'C', 'ç': 'c',
                'Ñ': 'N', 'ñ': 'n'
            };
            
            return str.replace(/[ÀÁÂÃÄÅàáâãäåÈÉÊËèéêëÌÍÎÏìíîïÒÓÔÕÖòóôõöÙÚÛÜùúûüÇçÑñ]/g, function(match) {
                return accentsMap[match] || match;
            });
        }
        
        // Função para aplicar máscara anti-acentos
        function applyNoAccentMask(element) {
            if (!element) return;
            
            element.addEventListener('input', function(event) {
                const cursorPosition = element.selectionStart;
                const originalValue = element.value;
                const cleanValue = removeAccents(originalValue);
                
                if (originalValue !== cleanValue) {
                    element.value = cleanValue;
                    // Mantém a posição do cursor
                    element.setSelectionRange(cursorPosition, cursorPosition);
                }
            });
            
            element.addEventListener('paste', function(event) {
                setTimeout(() => {
                    const originalValue = element.value;
                    const cleanValue = removeAccents(originalValue);
                    if (originalValue !== cleanValue) {
                        element.value = cleanValue;
                    }
                }, 10);
            });
        }
        
        // Lista de campos que devem ter a máscara anti-acentos
        const fieldsNoAccent = ['name', 'nickname', 'father', 'mother', 'spouse_name', 'tatto'];
        
        // Aplicar máscara quando o documento carregar
        document.addEventListener('DOMContentLoaded', function() {
            fieldsNoAccent.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (field) {
                    applyNoAccentMask(field);
                    console.log(`Máscara anti-acentos aplicada ao campo: ${fieldName}`);
                }
            });
        });
        
        async function searchCPF() {
            let loading = document.getElementById('loading');
            loading.classList.remove('d-none');
            let cpf = document.getElementById('cpf').value;
            if (cpf.length === 11) {
                const url = "{{url('cpf')}}/" + cpf;
                let response = await fetch(url);
                if (response.ok) {
                    let data = await response.json();
                    if (data.status === 404) {
                        loading.classList.add('d-none');
                        return;
                    }
                    // Aplicar remoção de acentos nos dados vindos da API
                    document.getElementById('name').value = removeAccents(data.nomeCompleto || '');
                    document.getElementById('nickname').value = removeAccents(data.nomeSocial || '');
                    document.getElementById('mother').value = removeAccents(data.nomeMae || '');
                    document.getElementById('birth_date').value = formatDate(data.dataNascimento);
                    document.getElementById('voter_registration').value = data.tituloEleitor;
                    document.getElementById('birth_city').value = data.municipioNaturalidade;
                    document.getElementById('uf_birth_city').value = data.ufNaturalidade;
                    document.getElementById('sex').value = data.sexo;
                    document.getElementById('code').value = data.cep;
                    document.getElementById('address').value = data.tipoLogradouro + " " + data.logradouro;
                    document.getElementById('number').value = data.numeroLogradouro;
                    document.getElementById('district').value = data.bairro;
                    document.getElementById('city').value = data.municipio;
                    document.getElementById('uf').value = data.uf;
                    document.getElementById('state').value;
                    document.getElementById('complement').value = data.complementoLogradouro;
                    document.getElementById('ddd').value = data.ddd;
                    document.getElementById('telephone').value = data.telefone;
                }
            }
            loading.classList.add('d-none');
        }

        function formatDate(date) {
            let data = new Date(date);
            let day = String(data.getDate()).padStart(2, '0');
            let month = String(data.getMonth() + 1).padStart(2, '0');
            let year = data.getFullYear();
            return day + "/" + month + "/" + year;
        }
    </script>
@endpush
