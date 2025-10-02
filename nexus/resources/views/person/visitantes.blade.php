{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 15/07/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="visitante_nome"
                    id="visitante_nome"
                    label="Nome"
                    placeholder="Nome do visitante"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="visitante_cpf"
                    id="visitante_cpf"
                    label="CPF"
                    placeholder="CPF do visitante"
                    data-inputmask="'mask': '999.999.999-99'"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="visitante_tipo_vinculo"
                    id="visitante_tipo_vinculo"
                    label="Tipo de Vínculo"
                    placeholder="Ex.: Advogado, Familiar, etc."
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
            onclick="addVisitante()"
        />
    </div>
</div>

@include('person.visitantesList') 