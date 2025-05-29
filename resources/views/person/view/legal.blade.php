@if($person->active_orcrim && !auth()->user()->can('sisfac'))
    @include('sisfac_block')
@else
    <div class="flex-row align-items-center p-2">
        <label>Mandado de Prisão:</label>
        <label class="mr-2">{!! $person->warrant == 0 ? '<span class="text-info text-md">NÂO</span>'
                        : '<span class="text-danger text-md">SIM</span>' !!}</label>
    </div>
    <div class="flex-row align-items-center p-2">
        <label>Evadido:</label>
        <label class="mr-2">{!! $person->evadido == 0 ? '<span class="text-success text-md">NÂO</span>'
                        : '<span class="text-warning text-md">SIM</span>' !!}</label>
    </div>
    <div class="card">
        <div class="card-header">
            <span>INFOPEN</span>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-1">
                    <x-adminlte-input
                        name="stuck"
                        id="stuck"
                        label="Preso"
                        value="{{$person->stuck == 0 ? 'Não' : 'Sim'}}"
                        disabled/>
                </div>
                <div class="form-group col-md-2">
                    <x-adminlte-input
                        name="detainee_registration"
                        id="detainee_registration"
                        label="Matricula"
                        value="{{$person->detainee_registration}}"
                        disabled
                    />
                </div>
                <div class="form-group col-md-2">
                    <x-adminlte-input name="detainee_date"
                                      id="detainee_date"
                                      label="Data da Prisão"
                                      disabled>
                        <x-slot name="appendSlot">
                            <div class="input-group-text bg-gradient-warning">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </x-slot>
                        {{$person->detainee_date}}
                    </x-adminlte-input>
                </div>
                <div class="form-group col-md-1">
                    <x-adminlte-input
                        name="detainee_uf"
                        id="detainee_uf"
                        label="UF"
                        value="{{$person->detainee_uf}}"
                        disabled/>
                </div>
                <div class="form-group col-md-3">
                    <x-adminlte-input
                        name="detainee_city"
                        id="detainee_city"
                        label="Município"
                        value="{{$person->detainee_city}}"
                        disabled
                    />
                </div>
                <div class="form-group col-md-2">
                    <x-adminlte-input
                        name="cela"
                        id="cela"
                        label="Estabelecimento/Cela"
                        value="{{$person->cela}}"
                        disabled
                    />
                </div>
            </div>
        </div>
    </div>
@endif
