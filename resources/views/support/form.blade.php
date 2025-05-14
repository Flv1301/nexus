{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="form-group col-md-4">
    <x-adminlte-input label="Título" id="title" name="title"
                      value="{{old('title') ?? $support->title}}"/>
</div>
<div class="form-group">
    @php
        $config = [
            "height" => "100",
            "toolbar" => [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        ]
    @endphp
    <x-adminlte-text-editor name="description" :config="$config"
                            placeholder="Descreva sua solicitação." label="Solicitação">
        {{old('description') ?? $support->description}}
    </x-adminlte-text-editor>
</div>
<div class="form-group">
    <x-adminlte-input-file id="files" name="files[]" igroup-size="sm" placeholder="Escolha o arquivo."
                           label="Arquivos" multiple>
        <x-slot name="prependSlot">
            <div class="input-group-text bg-lightblue">
                <i class="fas fa-upload"></i>
            </div>
        </x-slot>
    </x-adminlte-input-file>
</div>
<div class="card-footer">
    <x-adminlte-button
        type="submit"
        label="Gravar"
        theme="success"
        icon="fas fa-sm fa-save"
    />
</div>

