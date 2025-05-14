{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 11/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="form-row">
    <div class="form-group col-md-6">
        @php
            $options = [
                'whats_ticket' => 'Bilhetagem Whatsapp (.html)',
    ];
        @endphp
        <x-adminlte-select2
            name="file_type"
            id="file_type"
            label="Tipo de Anexo."
            data-placeholder="Selecione uma opção."
            onchange="selectUser()">
            <x-adminlte-options :options="$options" placeholder="Selecione uma opção."/>

        </x-adminlte-select2>
    </div>
    <div class="form-group col-md-6">
        <x-adminlte-input-file igroup-size="sm" name="files[]"
                               label="Arquivo"
                               placeholder="Selecione o arquivo .html"
                                multiple>
            <x-slot name="prependSlot">
                <div class="input-group-text bg-gradient-gray-dark">
                    <i class="fas fa-upload"></i>
                </div>
            </x-slot>
        </x-adminlte-input-file>
    </div>
</div>
