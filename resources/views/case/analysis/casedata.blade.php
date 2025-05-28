<div class="card">
    <div class="card-body">
        <div class="form-row">
            {{--             <div class="form-group col-md-3">
                <x-adminlte-input
                    name="id"
                    label="ID"
                    value="{{ $case->id }}"
                    disabled
                />
            </div> --}}
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="identifier"
                    label="Identificador"
                    value="{{ $case->identifier }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="name"
                    label="PJE"
                    value="{{ $case->name }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="gedoc"
                    label="GEDOC"
                    value="{{ $case->gedoc }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="saj"
                    label="SAJ"
                    value="{{ $case->saj }}"
                    disabled
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-5">
                <x-adminlte-input
                    name="subject"
                    label="Noticia de Fato"
                    value="{{ $case->subject }}"
                    disabled
                />
            </div>
            {{--             <div class="form-group col-md-5">
                <x-adminlte-input
                    name="cooperation_rif"
                    label="Controle/Cooperação/RIF"
                    value="{{ $case->cooperation_rif }}"
                    disabled
                />
            </div> --}}
            {{--             <div class="form-group col-md-2">
                <x-adminlte-input
                    name="year"
                    label="Ano"
                    value="{{ $case->year }}"
                    disabled
                />
            </div> --}}
        </div>
        <div class="form-row">
            {{--             <div class="form-group col-md-6">
                <x-adminlte-input
                    name="bop_number"
                    label="Boletim de Ocorrência"
                    value="{{ $case->bop_number }}"
                    disabled
                />
            </div> --}}
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="Processo"
                    label="PIC"
                    value="{{ $case->process }}"
                    disabled
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="portaria"
                    label="Portaria"
                    value="{{ $case->portaria }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="date"
                    label="Data"
                    type="date"
                    value="{{ $case->date }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="operation_number"
                    label="N da Operação"
                    value="{{ $case->operation_number }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="secondary_process"
                    label="Processo"
                    value="{{ $case->secondary_process }}"
                    disabled
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="cautelar"
                    label="Cautelar"
                    value="{{ $case->cautelar }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="grau"
                    label="Grau"
                    value="{{ $case->grau }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-3" id="second-degree-name-field-detail" @if($case->grau !== 'Segundo') style="display: none;" @endif>
                <x-adminlte-input
                    name="second_degree_name"
                    label="N. de segundo grau"
                    value="{{ $case->second_degree_name }}"
                    disabled
                />
            </div>
            @php
                $judgeRelatorLabel = '';
                if ($case->grau === 'Primeiro') {
                    $judgeRelatorLabel = 'Juiz';
                } elseif ($case->grau === 'Segundo') {
                    $judgeRelatorLabel = 'Relator';
                }
            @endphp
            <div class="form-group col-md-3" id="judge-relator-name-field-detail" @if(!in_array($case->grau, ['Primeiro', 'Segundo'])) style="display: none;" @endif>
                <x-adminlte-input
                    name="judge_relator_name"
                    label="{{ $judgeRelatorLabel }}"
                    value="{{ $case->judge_relator_name }}"
                    disabled
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <x-adminlte-text-editor name="resume"
                                        id="resume"
                                        label="Movimento"
                                        label-class="text-dark"
                                        disabled>
                    {!! $case->resume !!}
                </x-adminlte-text-editor>
            </div>
        </div>
    </div>
</div>
