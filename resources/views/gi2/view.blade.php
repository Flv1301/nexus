{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach($gi2s as $gi2)
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th>Operação:</th>
                                        <td>{{ $gi2->operacao }}</td>
                                        <th>Data/Hora:</th>
                                        <td>{{ \Illuminate\Support\Carbon::parse($gi2->date_time)->format('d/m/Y H:i') }}</td>
                                        <th>IMSI:</th>
                                        <td>{{ $gi2->imsi }}</td>
                                    </tr>
                                    <tr>
                                        <th>IMEI:</th>
                                        <td>{{ $gi2->imei }}</td>
                                        <th>MSISDN:</th>
                                        <td>{{ $gi2->msisdn }}</td>
                                        <th>Operador:</th>
                                        <td>{{ $gi2->operador }}</td>
                                    </tr>
                                    <tr>
                                        <th>País:</th>
                                        <td>{{ $gi2->pais }}</td>
                                        <th>Operador de Transmissão:</th>
                                        <td>{{ $gi2->transmission_operator }}</td>
                                        <th>Lat/Long:</th>
                                        <td>{{ \App\Helpers\MapsHerlper::coordinateConverterGi2((string)$gi2->latitude) }}
                                            ,{{ \App\Helpers\MapsHerlper::coordinateConverterGi2((string)$gi2->longitude) }}</td>
                                    </tr>
                                    @if($gi2->bopImei()->count())
                                        <tr>
                                            <th>BOP</th>
                                            <td>{{ $gi2->bopImei->bop->n_bop }}</td>
                                            <th>Data</th>
                                            <td>{{ \Illuminate\Support\Carbon::parse($gi2->bopImei->bop->dt_registro)->format('d/m/Y H:i') }}</td>
                                            <th>Tipo</th>
                                            <td>{{ $gi2->bopImei->bop->registros }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if($gi2->latitude && $gi2->longitude)
            <div class="card">
                <div class="card-body">
                    @include('gi2.maps_gi2')
                </div>
            </div>
        @endif
    </div>
</div>

