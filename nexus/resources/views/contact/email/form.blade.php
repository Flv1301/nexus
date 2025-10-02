<div class="form-group">
    <x-adminlte-input
        name="email"
        id="email"
        label="E-Mail"
        type="email"
        placeholder="E-Mail"
        value="{{ old('email') ?? $email->email ?? ''}}">
        <x-slot name="prependSlot">
            <div class="input-group-text text-black">
                <i class="fas fa-at"></i>
            </div>
        </x-slot>
    </x-adminlte-input>
</div>
