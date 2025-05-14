{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 29/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@php
    $options = [
        'document' => 'Arquivo',
        'whats_ticket' => 'Bilhetagem Whatsapp (.html)',
        'whats_access_log' => 'Log de Acesso Whatsapp (.html)',
        'facebook_access_log' => 'Log de Acesso Facebook (.html)',
        'instagram_access_log' => 'Log de Acesso Instagram (.html)',
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
        <x-adminlte-select2 name="file_type" label="Layout do Arquivo"
                            data-placeholder="Selecione o layout do arquivo">
            <x-adminlte-options :options="$options" placeholder="Selecione uma opção."/>
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
