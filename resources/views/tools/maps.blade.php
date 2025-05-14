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
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>Reverse Location Obfuscated ID</th>
            <th>Toggle</th>
        </tr>
        </thead>
        <tbody>
        @foreach($idsRepetidos as $id)
            <tr>
                <td>{{ $id }}</td>
                <td><button class="btn btn-info btn-toggle" data-marker-id="{{ $id }}">Ocultar</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
            var latitude = parseFloat(markerData['Latitude']);
            var longitude = parseFloat(markerData['Longitude']);
            var obfuscatedID = markerData['Reverse Location Obfuscated ID'];
            var color = generateColor(obfuscatedID); // Obtenha a cor com base no ID

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
            marker.bindPopup("ID: " + obfuscatedID).openPopup();
            marker.getElement().style.backgroundColor = color;

            var circle = L.circle([latitude, longitude], {
                color: color,
                fillColor: color,
                fillOpacity: 0.05,
                radius: parseFloat(markerData['Maps display radius(m)'])
            }).addTo(map);

            markerData.marker = marker;
            markerData.circle = circle;
        });

        $('.btn-toggle').click(function () {
            var markerId = $(this).data('marker-id');

            // Filtra todos os marcadores com o mesmo ID
            var markersToToggle = markers.filter(function (marker) {
                return marker['Reverse Location Obfuscated ID'] === markerId;
            });

            if (markersToToggle.length > 0) {
                markersToToggle.forEach(function(markerToToggle) {
                    if (map.hasLayer(markerToToggle.marker)) {
                        map.removeLayer(markerToToggle.marker);
                        map.removeLayer(markerToToggle.circle);
                    } else {
                        map.addLayer(markerToToggle.marker);
                        map.addLayer(markerToToggle.circle);
                    }
                });
            } else {
                console.error('Nenhum marcador encontrado com o ID:', markerId);
            }
        });

        function generateColor(id) {
            return '#' + Math.abs(hashCode(id)).toString(16).substring(0, 6);
        }

        function hashCode(str) {
            let hash = 0;
            if (str.length === 0) {
                return hash;
            }
            for (let i = 0; i < str.length; i++) {
                let char = str.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash; // Convert to 32bit integer
            }
            return hash;
        }
    </script>
@endpush


