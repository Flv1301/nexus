@php
    $options = [
        'document' => 'Documento',
        'whats_ticket' => 'Bilhetagem Whatsapp (.html)',
        'whats_access_log' => 'Log de Acesso Whatsapp (.html)',
        'facebook_access_log' => 'Log de Acesso Facebook (.html)',
        'instagram_access_log' => 'Log de Acesso Instagram (.html)',
    ];
@endphp
<div class="form-row col-md-12">
    <div class="form-group col-md-6">
        <x-adminlte-select2 name="file_type" label="Modelo do Documento"
                            data-placeholder="Selecione o modelo de documento">
            <x-adminlte-options :options="$options" placeholder="Selecione uma opção."/>
        </x-adminlte-select2>
    </div>
    <div class="form-group col-md-6">
        <x-adminlte-input-file igroup-size="md" name="files[]"
                               placeholder="Selecione o arquivo"
                               label="Documento"
                               multiple>
            <x-slot name="prependSlot">
                <div class="input-group-text bg-gradient-gray-dark">
                    <i class="fas fa-upload"></i>
                </div>
            </x-slot>
        </x-adminlte-input-file>
    </div>
</div>
