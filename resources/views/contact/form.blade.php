{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 18/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="overlay-wrapper">
    <div class="form-row">
        <div class="form-group col-md-2">
            <x-adminlte-input
                name="ddd"
                id="ddd"
                label="DDD"
                placeholder="DDD"
                class="mask-ddd"
                maxlength="2"
                value="{{ old('ddd') ?? $contact->ddd ?? ''}}">
                <x-slot name="prependSlot">
                    <div class="input-group-text text-black">
                        <i class="fas fa-phone"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="form-group col-md-4">
            <x-adminlte-input
                name="telephone"
                label="Telefone"
                placeholder="Telefone"
                id="telephone"
                class="mask-phone"
                value="{{old('telephone') ?? $contact->telephone ?? ''}}"
                maxlength="9">
                <x-slot name="prependSlot">
                    <div class="input-group-text text-black">
                        <i class="fas fa-phone"></i>
                    </div>
                </x-slot>
            </x-adminlte-input>
        </div>
        <div class="form-group col-md-4">
            <x-adminlte-input
                name="operator"
                id="operator"
                label="Operadora"
                placeholder="Operadora"
                value="{{ old('operator') ?? $contact->operator ?? ''}}"
            />
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <x-adminlte-input
                name="owner"
                id="owner"
                label="Proprietário"
                placeholder="Proprietário"
                value="{{ old('owner') ?? $contact->owner ?? ''}}"/>
        </div>
        @php
            $config = ['format' => 'DD/MM/YYYY'];
        @endphp
        <div class="form-group col-md-3">
            <x-adminlte-input-date name="start_link"
                                   id="start_link"
                                   :config="$config"
                                   placeholder="Data Ativação"
                                   label="Data Ativação">
                <x-slot name="appendSlot">
                    <div class="input-group-text bg-gradient-warning">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </x-slot>
                {{old('start_link')}}
            </x-adminlte-input-date>
        </div>
        <div class="form-group col-md-3">
            <x-adminlte-input-date name="end_link"
                                   id="end_link"
                                   :config="$config"
                                   placeholder="Data Cancelamento"
                                   label="Data Cancelamento">
                <x-slot name="appendSlot">
                    <div class="input-group-text bg-gradient-warning">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </x-slot>
                {{old('end_link')}}
            </x-adminlte-input-date>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-3">
            <x-adminlte-input
                name="imei"
                id="imei"
                label="IMEI"
                placeholder="IMEI"
                value="{{ old('imei') ?? $contact->imei ?? ''}}"/>
        </div>
        <div class="form-group col-md-3">
            <x-adminlte-input
                name="imsi"
                id="imsi"
                label="IMSI"
                placeholder="IMSI"
                value="{{ old('imsi') ?? $contact->imsi ?? ''}}"/>
        </div>
        <div class="form-group col-md-6">
            <x-adminlte-input
                name="device"
                id="device"
                label="Modelo do Aparelho"
                placeholder="Modelo do Aparelho"
                style="text-transform:uppercase;"
                value="{{ old('device') ?? $contact->device ?? ''}}"/>
        </div>
    </div>
</div>
