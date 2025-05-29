<div class="d-flex align-items-center p-2" >
    <div class="mr-2">
        <label>Mandado de Prisão:</label>
    </div>
    <div>
        <x-adminlte-input-switch
            data-on-text="SIM"
            data-off-text="NÃO"
            name="warrant"
            data-on-color="success"
            data-off-color="danger"
            :checked="old('warrant') ?? $person->warrant ?? false">
        </x-adminlte-input-switch>
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
                    placeholder="UF">
                    <option/>
                    @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                        <option {{old('detainee_uf') == $uf->name ? 'selected' : ($person->detainee_uf == $uf->name ? 'selected' : '')}}>
                            {{ $uf->name }}
                        </option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="detainee_city"
                    id="detainee_city"
                    label="Município"
                    placeholder="Município"
                    value="{{ old('detainee_city') ?? $person->detainee_city ?? ''}}"
                />
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
        </div>
    </div>
</div>
