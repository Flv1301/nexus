<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Ano</th>
                <th>Cor</th>
                <th>Placa</th>
                <th>Jurisdição</th>
                <th>Situação</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="tableVehicles">
            @if(isset($person) && $person->vehicles)
                @foreach($person->vehicles as $vehicle)
                    <tr>
                        <td>{{$vehicle->brand}}</td>
                        <td>{{$vehicle->model}}</td>
                        <td>{{$vehicle->year}}</td>
                        <td>{{$vehicle->color}}</td>
                        <td>{{$vehicle->plate}}</td>
                        <td>{{$vehicle->jurisdiction}}</td>
                        <td>{{$vehicle->status}}</td>
                        <td>
                            <i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i>
                            <input type="hidden" name="vehicles[{{$loop->index}}][id]" value="{{ $vehicle->id ?? '' }}">
                            <input type="hidden" name="vehicles[{{$loop->index}}][brand]" value="{{ $vehicle->brand ?? '' }}">
                            <input type="hidden" name="vehicles[{{$loop->index}}][model]" value="{{ $vehicle->model ?? '' }}">
                            <input type="hidden" name="vehicles[{{$loop->index}}][year]" value="{{ $vehicle->year ?? '' }}">
                            <input type="hidden" name="vehicles[{{$loop->index}}][color]" value="{{ $vehicle->color ?? '' }}">
                            <input type="hidden" name="vehicles[{{$loop->index}}][plate]" value="{{ $vehicle->plate ?? '' }}">
                            <input type="hidden" name="vehicles[{{$loop->index}}][jurisdiction]" value="{{ $vehicle->jurisdiction ?? '' }}">
                            <input type="hidden" name="vehicles[{{$loop->index}}][status]" value="{{ $vehicle->status ?? '' }}">
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
<script>
    function addVehicle() {
        const brandInput = document.getElementById('new_vehicle_brand');
        const modelInput = document.getElementById('new_vehicle_model');
        const yearInput = document.getElementById('new_vehicle_year');
        const colorInput = document.getElementById('new_vehicle_color');
        const plateInput = document.getElementById('new_vehicle_plate');
        const jurisdictionInput = document.getElementById('new_vehicle_jurisdiction');
        const statusInput = document.getElementById('new_vehicle_status');

        const brand = brandInput.value.trim();
        const model = modelInput.value.trim();
        const year = yearInput.value.trim();
        const color = colorInput.value.trim();
        const plate = plateInput.value.trim();
        const jurisdiction = jurisdictionInput.value.trim();
        const status = statusInput.value;

        if (brand === '') {
            alert('Campo Marca é obrigatório!');
            return;
        }

        const tableBody = document.getElementById('tableVehicles');
        const newRow = tableBody.insertRow();

        // Add cells
        const brandCell = newRow.insertCell();
        brandCell.textContent = brand;

        const modelCell = newRow.insertCell();
        modelCell.textContent = model;

        const yearCell = newRow.insertCell();
        yearCell.textContent = year;

        const colorCell = newRow.insertCell();
        colorCell.textContent = color;

        const plateCell = newRow.insertCell();
        plateCell.textContent = plate;

        const jurisdictionCell = newRow.insertCell();
        jurisdictionCell.textContent = jurisdiction;

        const statusCell = newRow.insertCell();
        statusCell.textContent = status;

        const actionsCell = newRow.insertCell();
        actionsCell.innerHTML = '<i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i>';

        // Add hidden inputs for form submission
        const fields = [
            {name: 'id', value: ''},
            {name: 'brand', value: brand},
            {name: 'model', value: model},
            {name: 'year', value: year},
            {name: 'color', value: color},
            {name: 'plate', value: plate},
            {name: 'jurisdiction', value: jurisdiction},
            {name: 'status', value: status}
        ];

        fields.forEach(field => {
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', `vehicles[][${field.name}]`);
            hiddenInput.setAttribute('value', field.value || '');
            newRow.appendChild(hiddenInput);
        });

        // Clear inputs
        brandInput.value = '';
        modelInput.value = '';
        yearInput.value = '';
        colorInput.value = '';
        plateInput.value = '';
        jurisdictionInput.value = '';
        statusInput.value = '';
    }

    // Reindex the hidden inputs before form submission
    $('form').submit(function() {
        $(this).find('#tableVehicles tr').each(function(index) {
            $(this).find('input[name^="vehicles"]').each(function() {
                const name = $(this).attr('name').replace(/vehicles\[\d*\]/, 'vehicles[' + index + ']');
                $(this).attr('name', name);
            });
        });
    });
</script>
@endpush 