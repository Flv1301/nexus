@if($person->active_orcrim && !auth()->user()->can('sisfac'))
    @include('sisfac_block')
@else
    <div class="card">
        <div class="card-header text-info">Veículos</div>
        <div class="card-body">
            @if($person->vehicles && $person->vehicles->count() > 0)
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($person->vehicles as $vehicle)
                            <tr>
                                <td>{{ $vehicle->brand ?? '-' }}</td>
                                <td>{{ $vehicle->model ?? '-' }}</td>
                                <td>{{ $vehicle->year ?? '-' }}</td>
                                <td>{{ $vehicle->color ?? '-' }}</td>
                                <td>{{ $vehicle->plate ?? '-' }}</td>
                                <td>{{ $vehicle->jurisdiction ?? '-' }}</td>
                                <td>{{ $vehicle->status ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhum veículo cadastrado para esta pessoa.
                </div>
            @endif
        </div>
    </div>
@endif 