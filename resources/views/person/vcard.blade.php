{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/03/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body col-md-6">
        <x-adminlte-input-file id="vcard" name="vcard[]" igroup-size="md" placeholder="Selecione o(s) arquivo(s) .vcf"
                               label="Contatos VCard" multiple>
            <x-slot name="prependSlot">
                <div class="input-group-text bg-lightblue">
                    <i class="fas fa-upload"></i>
                </div>
            </x-slot>
        </x-adminlte-input-file>
    </div>
</div>
