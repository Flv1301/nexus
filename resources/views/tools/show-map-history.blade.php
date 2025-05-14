@extends('adminlte::page')
@section('title', 'GI2')
@section('plugins.Datatables', true)

@section('content')
    @php
        set_time_limit(0);
    @endphp

    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css"/>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
        <script src='//unpkg.com/leaflet-arc/bin/leaflet-arc.min.js'></script>
    @endpush

    <div id="mapid" style="height: 600px;"></div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Coordenadas</th>
                    <th>Horário</th>
                    <th>Fonte</th>
                </tr>
                </thead>
                <tbody>
                @foreach($markers as $marker)
                    @php
                        $timestamp =$marker['timestamp'];
                        $hour = date('H', $timestamp);
                    @endphp
                    @if($hour >= 2 && $hour < 8)
                        <tr>
                           <td> <a href="https://www.google.com/maps?q={{ $marker['latitude'] }},{{ $marker['longitude'] }}" target="_blank">
                                   {{ $marker['latitude'] }} - {{ $marker['longitude'] }}
                               </a></td>
                            <td>{{ date('d-m-Y H:i:s', $timestamp) }}</td>
                            <td>{{ $marker['source'] }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('js')
        <script>
            const map = L.map('mapid').setView([-1.38005, -48.42637], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
                maxZoom: 18,
            }).addTo(map);

            const markers = {!! json_encode($markers) !!};

            markers.forEach(function(markerData) {
                var icon;
                var latitude = parseFloat(markerData['latitude']); // Corrigido aqui
                var longitude = parseFloat(markerData['longitude']); // Corrigido aqui
                var source = markerData['source'];
                var timestamp = markerData['timestamp'];

                icon = L.icon({
                    iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    shadowSize: [41, 41],
                    shadowAnchor: [12, 41]
                });

                var marker = L.marker([latitude, longitude], { icon: icon }).addTo(map);
                var marker = L.marker([latitude, longitude], { icon: icon }).addTo(map);
                marker.bindPopup(
                    '<a href="https://www.google.com/maps?q=' + latitude + ',' + longitude + '" target="_blank">' +
                    '{{ date('d-m-Y H:i:s', $timestamp) }} '+ source +
                    '</a>'
                ).openPopup();

                var circle = L.circle([latitude, longitude], {
                    color: 'red',
                    fillColor: 'red',
                    fillOpacity: 0.05,
                    radius: parseFloat(markerData['display_radius'])
                }).addTo(map);
            });
        </script>
    @endpush
@endsection
