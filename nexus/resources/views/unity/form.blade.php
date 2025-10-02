<div class="form-row">
    <div class="form-group col-md-7">
        <x-adminlte-input
            name="name"
            label="Nome da Unidade"
            placeholder="Digite o nome da unidade."
            value="{{old('name') ?? $unity->name ?? ''}}">
            <x-slot name="prependSlot">
                <div class="input-group-text text-black">
                    <i class="fas fa-building"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
    </div>
    <div class="form-group col-md-1">
        <x-adminlte-input
            name="ddd"
            label="DDD"
            id="ddd"
            class="mask-phone"
            value="{{old('ddd') ?? $unity->ddd ?? ''}}"
            maxlength="2">
            <x-slot name="prependSlot">
                <div class="input-group-text text-black">
                    <i class="fas fa-phone"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
    </div>
    <div class="form-group col-md-4">
        <x-adminlte-input
            name="contact"
            label="Telefone"
            id="contact"
            class="mask-phone"
            value="{{old('telephone') ?? $unity->telephone ?? ''}}"
            maxlength="9">
            <x-slot name="prependSlot">
                <div class="input-group-text text-black">
                    <i class="fas fa-phone"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-12">
        @include('address.create')
    </div>
</div>
