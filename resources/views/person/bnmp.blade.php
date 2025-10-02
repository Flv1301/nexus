{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 02/06/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="bnmp_numero_mandado"
                    id="bnmp_numero_mandado"
                    label="N. Mandado"
                    placeholder="Número do mandado"
                />
            </div>
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="bnmp_orgao_expedidor"
                    id="bnmp_orgao_expedidor"
                    label="Órgão Expedidor"
                    placeholder="Órgão expedidor"
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
            onclick="addBnmp()"
        />
    </div>
</div>

@include('person.bnmpList') 