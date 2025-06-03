<div class="card">
    <div class="card-header text-info">Veículos</div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="new_vehicle_brand"
                    id="new_vehicle_brand"
                    label="Marca"
                    placeholder="Marca do veículo"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="new_vehicle_model"
                    id="new_vehicle_model"
                    label="Modelo"
                    placeholder="Modelo do veículo"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_vehicle_year"
                    id="new_vehicle_year"
                    label="Ano"
                    placeholder="Ano"
                    maxlength="4"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_vehicle_color"
                    id="new_vehicle_color"
                    label="Cor"
                    placeholder="Cor"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="new_vehicle_plate"
                    id="new_vehicle_plate"
                    label="Placa"
                    placeholder="Placa"
                    class="mask-plate"
                    style="text-transform:uppercase;"
                    maxlength="8"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="new_vehicle_jurisdiction"
                    id="new_vehicle_jurisdiction"
                    label="Jurisdição"
                    placeholder="Jurisdição"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-select
                    name="new_vehicle_status"
                    id="new_vehicle_status"
                    label="Situação"
                >
                    <option value="">Selecione</option>
                    <option value="Regular">Regular</option>
                    <option value="Irregular">Irregular</option>
                    <option value="Roubado">Roubado</option>
                    <option value="Furtado">Furtado</option>
                    <option value="Apreendido">Apreendido</option>
                    <option value="Sucata">Sucata</option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-2 d-flex align-items-center justify-content-center">
                <x-adminlte-button
                    type="button"
                    icon="fas fa-plus"
                    theme="secondary"
                    label="Adicionar"
                    onclick="addVehicle()"
                />
            </div>
        </div>
    </div>
</div>

@include('person.vehiclesList')

@push('js')
<script>
    $(document).ready(function() {
        var vehicleIndex = {{ isset($person) && $person->vehicles->count() > 0 ? $person->vehicles->count() : 0 }};
    });
</script>
@endpush 