<div class="form-row">
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="name"
            label="PJE*"
            placeholder="PJE"
            style="text-transform:uppercase;"
            value="{{ old('name') ?? $case->name}}"
        />
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="process"
            label="PIC"
            placeholder="PIC"
            value="{{ old('process') ?? $case->process }}"
        />
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="secondary_process"
            label="Processo"
            placeholder="Número do Processo"
            value="{{ old('secondary_process') ?? $case->secondary_process }}"
        />
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="cautelar"
            label="Cautelar"
            placeholder="Número da Cautelar"
            value="{{ old('cautelar') ?? $case->cautelar }}"
        />
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="gedoc"
            label="GEDOC"
            placeholder="Número do GEDOC"
            value="{{ old('gedoc') ?? $case->gedoc }}"
        />
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="saj"
            label="SAJ"
            placeholder="Número do SAJ"
            value="{{ old('saj') ?? $case->saj }}"
        />
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="portaria"
            label="Portaria"
            placeholder="Número da Portaria"
            value="{{ old('portaria') ?? $case->portaria }}"
        />
    </div>
    <div class="form-group col-md-3">
        @php
            $config = ['format' => 'DD/MM/YYYY'];
        @endphp
        <x-adminlte-input-date 
            name="date"
            id="date"
            :config="$config"
            placeholder="Data"
            label="Data*"
            value="{{ old('date') ?? ($case->exists ? $case->date : '') }}">
            <x-slot name="appendSlot">
                <div class="input-group-text bg-gradient-warning">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </x-slot>
        </x-adminlte-input-date>
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-input
            name="operation_number"
            label="N da Operação"
            placeholder="Número da Operação"
            value="{{ old('operation_number') ?? $case->operation_number }}"
        />
    </div>
    <div class="form-group col-md-3">
        <x-adminlte-select name="grau" label="Grau*" placeholder="Selecione o grau.">
            <option value="Primeiro" {{ (old('grau') ?? $case->grau ?? '') == 'Primeiro' ? 'selected' : '' }}>Primeiro</option>
            <option value="Segundo" {{ (old('grau') ?? $case->grau ?? '') == 'Segundo' ? 'selected' : '' }}>Segundo</option>
        </x-adminlte-select>
    </div>
    <div class="form-group col-md-3" id="second-degree-name-field" style="display: none;">
        <x-adminlte-input
            name="second_degree_name"
            label="N. de segundo grau"
            placeholder="N. de segundo grau"
            value="{{ old('second_degree_name') ?? $case->second_degree_name }}"
        />
    </div>
    <div class="form-group col-md-3" id="judge-relator-name-field" style="display: none;">
        <x-adminlte-input
            name="judge_relator_name"
            label=""
            placeholder=""
            value="{{ old('judge_relator_name') ?? $case->judge_relator_name }}"
        />
    </div>
    <div class="form-group col-md-6">
        <x-adminlte-input
            name="subject"
            label="Notícia de Fato"
            placeholder="Notícia de Fato"
            style="text-transform:uppercase;"
            value="{{ old('subject') ?? $case->subject }}"
        />
    </div>
    <div class="form-group col-md-3">
        @if(isset($case) && $case->exists)
            <x-adminlte-input
                name="prazo_dias"
                label="Prazo Dias*"
                value="{{ old('prazo_dias') ?? $case->prazo_dias }} dias"
                readonly
            />
            <input type="hidden" name="prazo_dias" value="{{ $case->prazo_dias }}">
        @else
            <x-adminlte-select name="prazo_dias" label="Prazo Dias*" placeholder="Selecione o prazo.">
                <option value="">Selecione...</option>
                <option value="3" {{ (old('prazo_dias') ?? $case->prazo_dias ?? '') == '3' ? 'selected' : '' }}>3</option>
                <option value="5" {{ (old('prazo_dias') ?? $case->prazo_dias ?? '') == '5' ? 'selected' : '' }}>5</option>
                <option value="7" {{ (old('prazo_dias') ?? $case->prazo_dias ?? '') == '7' ? 'selected' : '' }}>7</option>
                <option value="15" {{ (old('prazo_dias') ?? $case->prazo_dias ?? '') == '15' ? 'selected' : '' }}>15</option>
                <option value="30" {{ (old('prazo_dias') ?? $case->prazo_dias ?? '') == '30' ? 'selected' : '' }}>30</option>
                <option value="60" {{ (old('prazo_dias') ?? $case->prazo_dias ?? '') == '60' ? 'selected' : '' }}>60</option>
                <option value="90" {{ (old('prazo_dias') ?? $case->prazo_dias ?? '') == '90' ? 'selected' : '' }}>90</option>
            </x-adminlte-select>
        @endif
    </div>
    @if(isset($case) && $case->exists)
        <div class="form-group col-md-3">
            <x-adminlte-select name="adicionar_dias" label="Adicionar Dias" placeholder="Adicionar dias ao prazo.">
                <option value="">Selecione...</option>
                <option value="1" {{ old('adicionar_dias') == '1' ? 'selected' : '' }}>1</option>
                <option value="3" {{ old('adicionar_dias') == '3' ? 'selected' : '' }}>3</option>
                <option value="5" {{ old('adicionar_dias') == '5' ? 'selected' : '' }}>5</option>
                <option value="15" {{ old('adicionar_dias') == '15' ? 'selected' : '' }}>15</option>
                <option value="30" {{ old('adicionar_dias') == '30' ? 'selected' : '' }}>30</option>
                <option value="60" {{ old('adicionar_dias') == '60' ? 'selected' : '' }}>60</option>
                <option value="90" {{ old('adicionar_dias') == '90' ? 'selected' : '' }}>90</option>
            </x-adminlte-select>
            <small class="text-muted">Os dias serão somados ao prazo atual quando o caso for atualizado.</small>
        </div>
    @endif
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
        <x-adminlte-text-editor name="resume" label="Movimento" id="resume-editor"
                                label-class="text-dark"
                                placeholder="Movimento do caso"
                                :config="$config">
            {{ old('resume') ?? $case->resume }}
        </x-adminlte-text-editor>

        <button type="button" class="btn btn-secondary mt-2" id="insert-date-btn">Inserir Data de Movimento</button>
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

@section('js')
<script>
    $(document).ready(function() {
        function toggleSecondDegreeNameField() {
            if ($('#grau').val() === 'Segundo') {
                $('#second-degree-name-field').show();
            } else {
                $('#second-degree-name-field').hide();
                $('#second-degree-name-field input').val(''); // Clear value when hidden
            }
        }

        function toggleJudgeRelatorNameField() {
            const grau = $('#grau').val();
            const judgeRelatorField = $('#judge-relator-name-field');
            const judgeRelatorInput = judgeRelatorField.find('input');
            const judgeRelatorLabel = judgeRelatorField.find('label');

            if (grau === 'Primeiro') {
                judgeRelatorLabel.text('Juiz');
                judgeRelatorInput.attr('placeholder', 'Nome do Juiz');
                judgeRelatorField.show();
            } else if (grau === 'Segundo') {
                judgeRelatorLabel.text('Relator');
                judgeRelatorInput.attr('placeholder', 'Nome do Relator');
                judgeRelatorField.show();
            } else {
                judgeRelatorField.hide();
                judgeRelatorInput.val(''); // Clear value when hidden
            }
        }

        toggleSecondDegreeNameField(); // Run on page load
        toggleJudgeRelatorNameField(); // Run on page load

        $('#grau').change(function() {
            toggleSecondDegreeNameField();
            toggleJudgeRelatorNameField();
        });

        // Script para inserir data no editor de texto
        $('#insert-date-btn').click(function() {
            const now = new Date();
            const day = String(now.getDate()).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const formattedDate = `[${day}/${month}/${year} ${hours}:${minutes}]\n`;

            // Assume the text editor is Summernote based on adminlte docs/examples
            $('#resume-editor').summernote('insertText', formattedDate);
        });
    });
</script>
@endsection

@section('css')
<style>
    /* Estilo para campos readonly */
    input[readonly], select[readonly] {
        background-color: #e9ecef !important;
        opacity: 1 !important;
        cursor: not-allowed !important;
        border: 1px solid #ced4da !important;
    }
    
    /* Estilo para labels de campos readonly */
    input[readonly] + label, 
    .form-group:has(input[readonly]) label {
        color: #6c757d !important;
    }
</style>
@endsection
