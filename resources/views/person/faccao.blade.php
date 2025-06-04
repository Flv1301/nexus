@can('sisfac')
<div class="card">
    <div class="card-header text-info">Dados da Facção</div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-1">
                <x-adminlte-select
                    name="active_orcrim"
                    id="active_orcrim"
                    label="Ativo"
                >
                    <option value="0" {{ (old('active_orcrim') ?? $person->active_orcrim ?? false) == '0' || !(old('active_orcrim') ?? $person->active_orcrim ?? false) ? 'selected' : '' }}>NÃO</option>
                    <option value="1" {{ (old('active_orcrim') ?? $person->active_orcrim ?? false) == '1' || (old('active_orcrim') ?? $person->active_orcrim ?? false) === true ? 'selected' : '' }}>SIM</option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="orcrim"
                    id="orcrim"
                    label="ORCRIM"
                    placeholder="Organização Criminosa"
                    style="text-transform:uppercase"
                    value="{{ old('orcrim') ?? $person->orcrim ?? ''}}"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="orcrim_office"
                    id="orcrim_office"
                    label="Cargo"
                    placeholder="Cargo na Organização"
                    style="text-transform:uppercase"
                    value="{{ old('orcrim_office') ?? $person->orcrim_office ?? ''}}"
                />
            </div>
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="orcrim_occupation_area"
                    id="orcrim_occupation_area"
                    label="Área de Atuação"
                    placeholder="Área de Atuação"
                    style="text-transform:uppercase"
                    value="{{ old('orcrim_occupation_area') ?? $person->orcrim_occupation_area ?? ''}}"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="orcrim_matricula"
                    id="orcrim_matricula"
                    label="Matrícula"
                    placeholder="Matrícula"
                    style="text-transform:uppercase"
                    value="{{ old('orcrim_matricula') ?? $person->orcrim_matricula ?? ''}}"
                />
            </div>
            <div class="form-group col-md-9">
                <x-adminlte-input
                    name="orcrim_padrinho"
                    id="orcrim_padrinho"
                    label="Padrinho"
                    placeholder="Padrinho"
                    style="text-transform:uppercase"
                    value="{{ old('orcrim_padrinho') ?? $person->orcrim_padrinho ?? ''}}"
                />
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <strong>Acesso Restrito:</strong> Você não tem permissão para visualizar ou editar informações de facção.
</div>
@endcan 