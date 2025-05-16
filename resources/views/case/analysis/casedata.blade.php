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
