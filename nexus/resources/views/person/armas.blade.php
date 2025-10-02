{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="arma_cac"
                    id="arma_cac"
                    label="CAC"
                    placeholder="Número do CAC"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="arma_marca"
                    id="arma_marca"
                    label="Marca"
                    placeholder="Marca"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="arma_modelo"
                    id="arma_modelo"
                    label="Modelo"
                    placeholder="Modelo"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="arma_calibre"
                    id="arma_calibre"
                    label="Calibre"
                    placeholder="Calibre"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="arma_sinarm"
                    id="arma_sinarm"
                    label="SINARM"
                    placeholder="Número do SINARM"
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
            onclick="addArma()"
        />
    </div>
</div>
@include('person.armasList') 