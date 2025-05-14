{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 18/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
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
                <x-adminlte-input-switch
                    label="Morto"
                    data-on-text="SIM"
                    data-off-text="NÃO"
                    name="dead"
                    data-on-color="success"
                    data-off-color="danger"
                    :checked="old('dead') ?? $person->dead ?? false"
                    :disabled="$isDisabled"
                />
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
                <x-adminlte-input
                    name="birth_city"
                    id="birth_city"
                    label="Município Nascimento"
                    placeholder="Município Nascimento"
                    style="text-transform:uppercase"
                    value="{{ old('birth_city') ?? $person->birth_city ?? ''}}"
                    :disabled="$isDisabled"
                />
            </div>
            <div class="form-group col-md-1">
                <x-adminlte-select
                    name="uf_birth_city"
                    id="uf_birth_city"
                    label="UF"
                    placeholder="UF"
                    :disabled="$isDisabled"
                >
                    <option/>
                    @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                        <option value="{{$uf->name}}"
                            {{ old('uf') == $uf->name ? 'selected'
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
        @can('sisfac')
            <div class="card">
                <div class="card-header text-info">Faccionado</div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <x-adminlte-input-switch
                                label="Ativo"
                                data-on-text="SIM"
                                data-off-text="NÃO"
                                name="active_orcrim"
                                data-on-color="success"
                                data-off-color="danger"
                                :checked="old('active_orcrim') ?? $person->active_orcrim ?? false">
                            </x-adminlte-input-switch>
                        </div>
                        <div class="form-group col-md-2">
                            <x-adminlte-input
                                name="orcrim"
                                id="orcrim"
                                label="ORCRIM"
                                placeholder="Organização Criminosa"
                                style="text-transform:uppercase"
                                value="{{ old('orcrim') ?? $person->orcrim ?? ''}}"
                            />
                        </div>
                        <div class="form-group col-md-3">
                            <x-adminlte-input
                                name="orcrim_office"
                                id="orcrim_office"
                                label="Cargo"
                                placeholder="Cargo na Organização"
                                style="text-transform:uppercase"
                                value="{{ old('orcrim_office') ?? $person->orcrim_office ?? ''}}"
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <x-adminlte-input
                                name="orcrim_occupation_area"
                                id="orcrim_occupation_area"
                                label="Área de Atuação"
                                placeholder="Área de Atuação"
                                style="text-transform:uppercase"
                                value="{{ old('orcrim_occupation_area') ?? $person->orcrim_occupation_area ?? ''}}"
                            />
                        </div>
                    </div>
                    <div class="form-row">
                        {{--                    <div class="form-group col-md-2">--}}
                        {{--                        <x-adminlte-select--}}
                        {{--                            name="hair"--}}
                        {{--                            id="hair"--}}
                        {{--                            label="Cor do cabelo"--}}
                        {{--                            placeholder="Cor do cabelo">--}}
                        {{--                            <option/>--}}
                        {{--                            <option--}}
                        {{--                                value="Branca" {{ old('hair') == 'Branca' ? 'selected' : ($person->hair == 'Branca' ? 'selected' : '')}}>--}}
                        {{--                                Branco--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Castanho Claro" {{ old('hair') == 'Castanho Claro' ? 'selected' : ($person->hair == 'Castanho Claro' ? 'selected' : '')}}>--}}
                        {{--                                Castanho Claro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Castanho Escuro" {{ old('hair') == 'Castanho Escuro' ? 'selected' : ($person->hair == 'Castanho Escuro' ? 'selected' : '')}}>--}}
                        {{--                                Castanho Escuro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Louro Claro" {{ old('hair') == 'Louro Claro' ? 'selected' : ($person->hair == 'Louro Claro' ? 'selected' : '')}}>--}}
                        {{--                                Louro Claro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Louro Escuro" {{ old('hair') == 'Louro Escuro' ? 'selected' : ($person->hair == 'Louro Escuro' ? 'selected' : '')}}>--}}
                        {{--                                Louro Escuro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Cinza" {{ old('hair') == 'Cinza' ? 'selected' : ($person->hair == 'Cinza' ? 'selected' : '')}}>--}}
                        {{--                                Cinza--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Grisalho" {{ old('hair') == 'Grisalho' ? 'selected' : ($person->hair == 'Grisalho' ? 'selected' : '')}}>--}}
                        {{--                                Grisalho--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Ruivo" {{ old('hair') == 'Ruivo' ? 'selected' : ($person->hair == 'Ruivo' ? 'selected' : '')}}>--}}
                        {{--                                Ruivo--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Preto" {{ old('uf') == 'Preto' ? 'selected' : ($person->hair == 'Preto' ? 'selected' : '')}}>--}}
                        {{--                                Preto--}}
                        {{--                            </option>--}}
                        {{--                        </x-adminlte-select>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="form-group col-md-2">--}}
                        {{--                        <x-adminlte-select--}}
                        {{--                            name="eye"--}}
                        {{--                            id="eye"--}}
                        {{--                            label="Cor dos olhos"--}}
                        {{--                            placeholder="Cor dos olhos">--}}
                        {{--                            <option/>--}}
                        {{--                            <option--}}
                        {{--                                value="Azul Claro" {{ old('eye') == 'Azul Claro' ? 'selected' : ($person->eye == 'Azul Claro' ? 'selected' : '')}}>--}}
                        {{--                                Azul Claro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Azul Escuro" {{ old('eye') == 'Azul Escuro' ? 'selected' : ($person->eye == 'Azul Escuro' ? 'selected' : '')}}>--}}
                        {{--                                Azul Escuro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Castanho Claro" {{ old('eye') == 'Castanho Claro' ? 'selected' : ($person->eye == 'Castanho Claro' ? 'selected' : '')}}>--}}
                        {{--                                Castanho Claro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Castanho Escuro" {{ old('eye') == 'Castanho Escuro' ? 'selected' : ($person->eye == 'Castanho Escuro' ? 'selected' : '')}}>--}}
                        {{--                                Castanho Escuro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Cinza Claro" {{ old('eye') == 'Cinza Claro' ? 'selected' : ($person->eye == 'Cinza Claro' ? 'selected' : '')}}>--}}
                        {{--                                Cinza Claro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Cinza Escuro" {{ old('eye') == 'Cinza Escuro' ? 'selected' : ($person->eye == 'Cinza Escuro' ? 'selected' : '')}}>--}}
                        {{--                                Cinza Escuro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Verde Claro" {{ old('eye') == 'Verde Claro' ? 'selected' : ($person->eye == 'Verde Claro' ? 'selected' : '')}}>--}}
                        {{--                                Verde Claro--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Verde Escuro" {{ old('uf') == 'Verde Escuro' ? 'selected' : ($person->eye == 'Verde Escuro' ? 'selected' : '')}}>--}}
                        {{--                                Verde Escuro--}}
                        {{--                            </option>--}}
                        {{--                        </x-adminlte-select>--}}
                        {{--                    </div>--}}
                        {{--                    <div class="form-group col-md-2">--}}
                        {{--                        <x-adminlte-select--}}
                        {{--                            name="color"--}}
                        {{--                            id="color"--}}
                        {{--                            label="Cor da pele"--}}
                        {{--                            placeholder="Cor da pele">--}}
                        {{--                            <option/>--}}
                        {{--                            <option--}}
                        {{--                                value="Branca" {{ old('color') == 'Branca' ? 'selected' : ($person->color == 'Branca' ? 'selected' : '')}}>--}}
                        {{--                                Branca--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Morena" {{ old('color') == 'Morena' ? 'selected' : ($person->color == 'Morena' ? 'selected' : '')}}>--}}
                        {{--                                Morena--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Negra" {{ old('color') == 'Negra' ? 'selected' : ($person->color == 'Negra' ? 'selected' : '')}}>--}}
                        {{--                                Negra--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Parda" {{ old('color') == 'Parda' ? 'selected' : ($person->color == 'Parda' ? 'selected' : '')}}>--}}
                        {{--                                Parda--}}
                        {{--                            </option>--}}
                        {{--                            <option--}}
                        {{--                                value="Preta" {{ old('color') == 'Preta' ? 'selected' : ($person->color == 'Preta' ? 'selected' : '')}}>--}}
                        {{--                                Preta--}}
                        {{--                            </option>--}}
                        {{--                        </x-adminlte-select>--}}
                        {{--                    </div>--}}
                    </div>
                </div>
            </div>
        @endcan
        {{--        <div class="form-row">--}}
        {{--            <div class="form-group col-md-11">--}}
        {{--                <x-adminlte-input--}}
        {{--                    name="occupation"--}}
        {{--                    id="occupation"--}}
        {{--                    label="Ocupação Principal"--}}
        {{--                    placeholder="Ocupação Principal"--}}
        {{--                    style="text-transform:uppercase"--}}
        {{--                    value="{{ old('occupation') ?? $person->occupation ?? ''}}"--}}
        {{--                />--}}
        {{--            </div>--}}
        {{--            <div class="form-group col-md-1">--}}
        {{--                <x-adminlte-input--}}
        {{--                    name="occupation_year"--}}
        {{--                    id="occupation_year"--}}
        {{--                    label="Ano"--}}
        {{--                    placeholder="Ano"--}}
        {{--                    maxlength="4"--}}
        {{--                    class="mask-year"--}}
        {{--                    value="{{ old('occupation_year') ?? $person->occupation_year ?? ''}}"--}}
        {{--                />--}}
        {{--            </div>--}}
        {{--        </div>--}}
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
                    document.getElementById('name').value = data.nomeCompleto;
                    document.getElementById('nickname').value = data.nomeSocial;
                    document.getElementById('mother').value = data.nomeMae;
                    document.getElementById('birth_date').value = formatDate(data.dataNascimento);
                    document.getElementById('voter_registration').value = data.tituloEleitor;
                    // document.getElementById('occupation').value = data.ocupacaoPrincipal;
                    // document.getElementById('occupation_year').value = data.anoExercicioOcupacao;
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
