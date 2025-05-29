{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="vinculo_name"
                    id="vinculo_name"
                    label="Nome"
                    placeholder="Nome do Vínculo"
                />
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="vinculo_alcunha"
                    id="vinculo_alcunha"
                    label="Alcunha"
                    placeholder="Alcunha do Vínculo"
                />
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="vinculo_cpf"
                    id="vinculo_cpf"
                    label="CPF"
                    placeholder="CPF do Vínculo"
                    class="mask-cpf"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="vinculo_tipo_vinculo"
                    id="vinculo_tipo_vinculo"
                    label="Tipo de Vínculo"
                    placeholder="Tipo de Vínculo"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="vinculo_orcrim"
                    id="vinculo_orcrim"
                    label="Orcrim"
                    placeholder="Orcrim"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="vinculo_cargo"
                    id="vinculo_cargo"
                    label="Cargo"
                    placeholder="Cargo"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="vinculo_area_atuacao"
                    id="vinculo_area_atuacao"
                    label="Área de Atuação"
                    placeholder="Área de Atuação"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <x-adminlte-input
                    name="vinculo_matricula"
                    id="vinculo_matricula"
                    label="Matrícula"
                    placeholder="Matrícula"
                />
            </div>
        </div>
    </div>
    <div class="card-footer">
        <x-adminlte-button
            type="button"
            icon="fas fa-plus"
            theme="secondary"
            label="Adicionar"
            onclick="addVinculoOrcrim()"
        />
    </div>
</div>
@include('person.vinculoOrcrimsList') 