<div class="form-row">
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="name"
            label="Nome*"
            placeholder="Nome"
            style="text-transform:uppercase;"
            value="{{ old('name') ?? $case->name}}"
        />
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="process"
            label="Processo"
            placeholder="Processo"
            value="{{ old('process') ?? $case->process }}"
        />
    </div>
    <div class="form-group col-md-6">
        <x-adminlte-input
            name="subject"
            label="Assunto"
            placeholder="Assunto"
            style="text-transform:uppercase;"
            value="{{ old('subject') ?? $case->subject }}"
        />
    </div>

</div>
<div class="form-row">
    <div class="form-group col-md-12">
        @php
            $config = [
                "height" => "100",
                "toolbar" => [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ],
            ]
        @endphp
        <x-adminlte-text-editor name="resume" label="Resumo"
                                label-class="text-dark"
                                placeholder="Resumo do caso"
                                :config="$config">
            {{ old('resume') ?? $case->resume }}
        </x-adminlte-text-editor>
    </div>
    @isset($case->status)
        <div class="form-group col-md-3">
            <x-adminlte-select name="status" label="Status" placeholder="Selecione uma opção.">
                @foreach(\App\Enums\StatusCaseEnum::cases() as $status)
                    <option
                        value="{{$status->name}}"
                        class="font-weight-bold font-italic"
                        {{$status->name == $case->status ? 'selected' : ''}}>
                        {{$status->name}}
                    </option>
                @endforeach
            </x-adminlte-select>
        </div>
    @endisset
</div>
<div class="">
    <span class="text-info">* Campos obrigatórios</span>
</div>
