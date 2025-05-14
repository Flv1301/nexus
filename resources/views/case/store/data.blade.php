{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 11/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
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
        <x-adminlte-select2
            name="type_id"
            label="Tipo*"
            data-placeholder="Selecione uma opção.">
            <option/>
            @foreach($types as $type)
                <option
                    value="{{$type->id ?? old('type_id')}}"
                    @if(old('type_id') == $type->id || $type->id == $case->type_id) selected @endif>
                    {{$type->name}}
                </option>
            @endforeach
        </x-adminlte-select2>
    </div>
    <div class="form-group col-md-1">
        <x-adminlte-input
            type="number"
            name="phase"
            label="Fase*"
            placeholder="Fase"
            min="1"
            max="10"
            value="{{ old('phase') ?? $case->phase ?? 1}}"/>
    </div>
    <div class="form-group col-md-5">
        <x-adminlte-select2
            name="case_link"
            label="Vinculo"
            data-placeholder="Selecione uma opção.">
            <option/>
            @foreach($cases as $caselink)
                <option
                    value="{{ old('case_id') ?? $case->id }}"
                    @if(old('case_id') == $caselink->id || $caselink->id == $case->case_id) selected @endif>
                    {{$caselink->identifier . ' - ' . $caselink->name}}
                </option>
            @endforeach
        </x-adminlte-select2>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <x-adminlte-input
            name="subject"
            label="Assunto"
            placeholder="Assunto"
            style="text-transform:uppercase;"
            value="{{ old('subject') ?? $case->subject }}"
        />
    </div>
    <div class="form-group col-md-4">
        <x-adminlte-input
            name="cooperation_rif"
            label="Controle/Cooperação/RIF"
            placeholder="Controle/Cooperação/RIF"
            value="{{ old('cooperation_rif') ?? $case->cooperation_rif}}"
        />
    </div>
    <div class="form-group col-md-2">
        <x-adminlte-input
            name="year"
            label="Ano"
            placeholder="Ano"
            value="{{ old('year') ?? $case->year }}"
        />
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        <x-adminlte-input
            name="bop_number"
            label="Boletim de Ocorrência"
            placeholder="Boletim de Ocorrência"
            value="{{ old('bop_number') ?? $case->bop_number }}"
        />
    </div>
    <div class="form-group col-md-6">
        <x-adminlte-input
            name="process"
            label="Processo"
            placeholder="Processo"
            value="{{ old('process') ?? $case->process }}"
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
