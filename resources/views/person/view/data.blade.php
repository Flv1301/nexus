<div class="card">
    <div class="card-header">
        <span class="text-info">Atualizado: {{date('d/m/Y H:i', strtotime($person->updated_at))}}</span>
    </div>
    <div class="card-body">
        <div class="card">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <x-adminlte-input
                            name="cpf"
                            id="cpf"
                            label="CPF"
                            class="mask-cpf-number"
                            value="{{$person->cpf}}"
                            disabled
                        />
                    </div>
                    <div class="form-group col-md-4">
                        <x-adminlte-input
                            name="name"
                            id="name"
                            label="Nome completo"
                            style="text-transform:uppercase"
                            value="{{$person->name}}"
                            disabled
                        />
                    </div>
                    <div class="form-group col-md-3">
                        <x-adminlte-input
                            name="nickname"
                            id="nickname"
                            label="Alcunha"
                            style="text-transform:uppercase"
                            value="{{$person->nickname}}"
                            disabled
                        />
                    </div>
                    <div class="form-group col-md-1">
                        <x-adminlte-input-switch
                            data-on-text="SIM"
                            data-off-text="NÃO"
                            label="Morto"
                            name="dead"
                            data-on-color="success"
                            data-off-color="danger"
                            :checked="$person->dead ?? false"
                            disabled>
                        </x-adminlte-input-switch>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <x-adminlte-input
                            name="sex"
                            id="sex"
                            label="Sexo"
                            value="{{$person->sex}}"
                            disabled
                        >
                        </x-adminlte-input>
                    </div>
                    <div class="form-group col-md-3">
                        <x-adminlte-input name="birth_date"
                                          id="birth_date"
                                          label="Data Nascimento"
                                          disabled
                                          value="{{$person->birth_date}}">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-gradient-warning">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="form-group col-md-3">
                        <x-adminlte-input
                            name="rg"
                            id="rg"
                            label="RG"
                            value="{{$person->rg}}"
                            disabled
                        />
                    </div>
                    <div class="form-group col-md-3">
                        <x-adminlte-input
                            name="voter_registration"
                            id="voter_registration"
                            label="Titulo Eleitor"
                            value="{{$person->voter_registration}}"
                            disabled
                        />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <x-adminlte-input
                            name="birth_city"
                            id="birth_city"
                            label="Município Nascimento"
                            value="{{$person->birth_city}}"
                            disabled
                        />
                    </div>
                    <div class="form-group col-md-1">
                        <x-adminlte-input
                            name="uf_birth_city"
                            id="uf_birth_city"
                            label="UF"
                            value="{{$person->uf_birth_city}}"
                            disabled
                        />
                    </div>
                    <div class="form-group col-md-4">
                        <x-adminlte-input
                            name="father"
                            id="father"
                            label="Nome do pai"
                            value="{{$person->father}}"
                            disabled
                        />
                    </div>
                    <div class="form-group col-md-4">
                        <x-adminlte-input
                            name="mother"
                            id="mother"
                            label="Nome da mãe"
                            value="{{$person->mother}}"
                            disabled
                        />
                    </div>
                    <div class="form-group col-md-12">
                        <x-adminlte-input
                            name="tatto"
                            id="tatto"
                            label="Tatuagem"
                            value="{{$person->tatto}}"
                            disabled
                        />
                    </div>
                </div>
            </div>
        </div>
        @can('sisfac')
            <div class="card bg-gradient-light">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <x-adminlte-input-switch
                                data-on-text="SIM"
                                data-off-text="NÃO"
                                label="Ativo"
                                name="active_orcrim"
                                data-on-color="success"
                                data-off-color="danger"
                                disabled
                                :checked="$person->active_orcrim ?? false">
                            </x-adminlte-input-switch>
                        </div>
                        <div class="form-group col-md-2">
                            <x-adminlte-input
                                name="orcrim"
                                id="orcrim"
                                label="ORCRIM"
                                value="{{ $person->orcrim}}"
                                disabled
                            />
                        </div>
                        <div class="form-group col-md-3">
                            <x-adminlte-input
                                name="orcrim_office"
                                id="orcrim_office"
                                label="Cargo"
                                value="{{ $person->orcrim_office}}"
                                disabled
                            />
                        </div>
                        <div class="form-group col-md-6">
                            <x-adminlte-input
                                name="orcrim_occupation_area"
                                id="orcrim_occupation_area"
                                label="Área de Atuação"
                                value="{{ $person->orcrim_occupation_area}}"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        <div class="form-group col-md-12">
            <x-adminlte-text-editor name="observation" label="Observações" label-class="text-dark"
                                    placeholder="Observações"
                                    :config="$config" disabled>
                {!! $person->observation  !!}
            </x-adminlte-text-editor>
        </div>
    </div>
</div>
