<div class="d-flex align-items-center p-2">
    <div class="d-flex align-items-center mr-4">
        <div class="mr-2">
            <label>Mandado de Prisão:</label>
        </div>
        <div style="min-width: 80px;">
            <x-adminlte-select
                name="warrant"
                id="warrant"
            >
                <option value="0" {{ (old('warrant') ?? $person->warrant ?? false) == '0' || !(old('warrant') ?? $person->warrant ?? false) ? 'selected' : '' }}>NÃO</option>
                <option value="1" {{ (old('warrant') ?? $person->warrant ?? false) == '1' || (old('warrant') ?? $person->warrant ?? false) === true ? 'selected' : '' }}>SIM</option>
            </x-adminlte-select>
        </div>
    </div>
    <div class="d-flex align-items-center">
        <div class="mr-2">
            <label>Evadido?</label>
        </div>
        <div style="min-width: 80px;">
            <x-adminlte-select
                name="evadido"
                id="evadido"
            >
                <option value="0" {{ (old('evadido') ?? $person->evadido ?? false) == '0' || !(old('evadido') ?? $person->evadido ?? false) ? 'selected' : '' }}>NÃO</option>
                <option value="1" {{ (old('evadido') ?? $person->evadido ?? false) == '1' || (old('evadido') ?? $person->evadido ?? false) === true ? 'selected' : '' }}>SIM</option>
            </x-adminlte-select>
        </div>
    </div>
</div>
<div class="card">
    <x-loading/>
    <div class="card-header">
        <span>INFOPEN</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-1">
                <x-adminlte-select
                    name="stuck"
                    id="stuck"
                    label="Preso"
                    placeholder="preso">
                    <option/>
                    <option value="0" {{ old('stuck') == 0 ? 'selected' : ($person->stuck == 0 ? 'selected' : '')}}>
                        Não
                    </option>
                    <option value="1" {{ old('stuck') == 1 ? 'selected' : ($person->stuck == 1 ? 'selected' : '')}}>
                        Sim
                    </option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="detainee_registration"
                    id="detainee_registration"
                    label="Matricula"
                    placeholder="Matricula"
                    value="{{ old('detainee_registration') ?? $person->detainee_registration ?? ''}}"
                />
            </div>
            <div class="form-group d-flex align-items-center mt-3 mr-3">
                <x-adminlte-button
                    type="button"
                    icon="fas fa-search"
                    theme="info"
                    id="detainee-button"
                    label="Consultar"
                    onclick="searchDetainee()"
                    disabled
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input-date name="detainee_date"
                                       id="detainee_date"
                                       :config="$config"
                                       placeholder="Data da Prisão"
                                       label="Data da Prisão"
                                       value="{{old('detainee_date') ?? $person->detainee_date ?? ''}}"
                >
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                    {{old('birthday')}}
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-1">
                <x-adminlte-select
                    name="detainee_uf"
                    id="detainee_uf"
                    label="UF"
                    placeholder="UF"
                    onchange="loadCitiesByUF('detainee_uf', 'detainee_city')"
                >
                    <option/>
                    @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                        <option value="{{$uf->name}}" {{old('detainee_uf') == $uf->name ? 'selected' : ($person->detainee_uf == $uf->name ? 'selected' : '')}}>
                            {{ $uf->name }}
                        </option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-select
                    name="detainee_city"
                    id="detainee_city"
                    label="Município"
                    placeholder="Selecione primeiro a UF"
                >
                    <option value="">Selecione primeiro a UF</option>
                    @if(old('detainee_city') || $person->detainee_city)
                        <option value="{{ old('detainee_city') ?? $person->detainee_city ?? '' }}" selected>
                            {{ old('detainee_city') ?? $person->detainee_city ?? '' }}
                        </option>
                    @endif
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="cela"
                    id="cela"
                    label="Estabelecimento/Cela"
                    placeholder="Estabelecimento/Cela"
                    value="{{ old('cela') ?? $person->cela ?? ''}}"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="situacao_infopen"
                    id="situacao_infopen"
                    label="Situação"
                    placeholder="Situação atual"
                    value="{{ old('situacao_infopen') ?? $person->situacao_infopen ?? ''}}"
                />
            </div>
        </div>
    </div>
</div>
