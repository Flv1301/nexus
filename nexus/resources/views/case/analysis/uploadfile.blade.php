@php
    $options = [
        'document' => 'Documento Padrão',
    ];
@endphp
<div class="">
    <div class="col-md-12">
        <x-adminlte-input
            name="name"
            id="name"
            style="text-transform:uppercase"
            label="Alias do Arquivo (Opcional)"
            placeholder="Alias do arquivo. (Opcional)"
            value="{{ old('name') }}">
            <x-slot name="prependSlot">
                <div class="input-group-text">
                    <i class="fas fa-signature"></i>
                </div>
            </x-slot>
        </x-adminlte-input>
    </div>
    <div class="form-group col-md-12">
        <x-adminlte-select2 name="file_type" label="Layout do Arquivo" value="{{ old('file_type', 'document') }}">
            data-placeholder="Selecione o layout do arquivo">
            {{--            <x-adminlte-options :options="$options" placeholder="Selecione uma opção." />--}}
            <option value="document">Documento Padrão</option>
            <x-slot name="prependSlot">
                <div class="input-group-text">
                    <i class="fas fa-file-import"></i>
                </div>
            </x-slot>
        </x-adminlte-select2>
    </div>
    <div class="form-group col-md-12">
        <x-adminlte-input-file igroup-size="md" name="files[]"
                               placeholder="Selecione o arquivo."
                               label="Documento">
            <x-slot name="prependSlot">
                <div class="input-group-text">
                    <i class="fas fa-upload"></i>
                </div>
            </x-slot>
        </x-adminlte-input-file>
    </div>
</div>
