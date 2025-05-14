{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 16/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="id"
                    label="ID"
                    value="{{ $case->id }}"
                    disabled
                />
            </div>
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
                    label="Nome"
                    value="{{ $case->name }}"
                    disabled
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="type_id"
                    label="Tipo"
                    value="{{ $case->type->name }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="phase"
                    label="Fase"
                    value="{{ $case->phase }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="case_link"
                    label="Vinculo"
                    value=""
                    disabled
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-5">
                <x-adminlte-input
                    name="subject"
                    label="Assunto"
                    value="{{ $case->subject }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-5">
                <x-adminlte-input
                    name="cooperation_rif"
                    label="Controle/Cooperação/RIF"
                    value="{{ $case->cooperation_rif }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="year"
                    label="Ano"
                    value="{{ $case->year }}"
                    disabled
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="bop_number"
                    label="Boletim de Ocorrência"
                    value="{{ $case->bop_number }}"
                    disabled
                />
            </div>
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="Processo"
                    label="Processo"
                    value="{{ $case->process }}"
                    disabled
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <x-adminlte-text-editor name="resume"
                                        id="resume"
                                        label="Resumo"
                                        label-class="text-dark"
                                        disabled>
                    {!! $case->resume !!}
                </x-adminlte-text-editor>
            </div>
        </div>
    </div>
</div>
