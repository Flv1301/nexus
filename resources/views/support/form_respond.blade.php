{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
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
    <x-adminlte-text-editor name="response" id="response" :config="$config" placeholder="Descreva sua resposta."
                            label="Resposta a Solicitação">
        {{old('response') ?? $support_response->response}}
    </x-adminlte-text-editor>
</div>
<div class="form-group">
    <x-adminlte-input-file id="files" name="files[]" igroup-size="md" placeholder="Escolha o arquivo."
                           label="Arquivos" multiple>
        <x-slot name="prependSlot">
            <div class="input-group-text bg-lightblue">
                <i class="fas fa-upload"></i>
            </div>
        </x-slot>
    </x-adminlte-input-file>
</div>
<div class="form-group">
    <x-adminlte-select2 id="redirect_user" name="redirect_user" label="Redirecionar para Usuário" igroup-size="md">
        <option/>
        @foreach($users as $user)
            <option value="{{$user->id}}">{{$user->nickname}}</option>
        @endforeach
        <x-slot name="prependSlot">
            <div class="input-group-text bg-lightblue">
                <i class="fas fa-user"></i>
            </div>
        </x-slot>
    </x-adminlte-select2>
</div>
<div class="form-group">
    <x-adminlte-select2 id="status" name="redirect_user" label="Status" igroup-size="md" name="status">
        <option/>
        <option value="Analise" {{old('status') == 'Analise' ? 'selected' : ''}}>Analise</option>
        <option value="Desenvolvimento" {{old('status') == 'Desenvolvimento' ? 'selected' : ''}}>Desenvolvimento</option>
        <option value="Manutencao" {{old('status') == 'Manutencao' ? 'selected' : ''}}>Manutencao</option>
        <option value="Fechado" {{old('status') == 'Fechado' ? 'selected' : ''}}>Fechado</option>
        <option value="Rejeitado" {{old('status') == 'Rejeitado' ? 'selected' : ''}}>Rejeitado</option>
        <x-slot name="prependSlot">
            <div class="input-group-text bg-lightblue">
                <i class="fas fa-battery-half"></i>
            </div>
        </x-slot>
    </x-adminlte-select2>
</div>
<div class="card-footer">
    <x-adminlte-button
        type="submit"
        label="Gravar"
        theme="success"
        icon="fas fa-sm fa-save"
    />
</div>

