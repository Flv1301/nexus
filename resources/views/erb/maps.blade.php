@php

    use App\Models\Erb;
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
    <div class="card-header">Adicionar Azimute <button id="excluir-poligonos" class="btn btn-danger">Excluir Polígonos</button></div>
    <div class="card-body">
    <form id="sector-form" class="row g-3">
        <div class="col-md-2">
            <label for="latitude" class="form-label">Latitude</label>
            <input type="text" class="form-control" id="latitude">
        </div>
        <div class="col-md-2">
            <label for="longitude" class="form-label">Longitude</label>
            <input type="text" class="form-control" id="longitude">
        </div>
        <div class="col-md-2">
            <label for="margem" class="form-label">Margem</label>
            <input type="text" value="120" class="form-control" id="margem">
        </div>
        <div class="col-md-2">
            <label for="azimute" class="form-label">Azimute</label>
            <input type="text" class="form-control" id="azimute">
        </div>
        <div class="col-md-2">
            <label for="distancia" class="form-label">Distância</label>
            <input type="text" value="2" class="form-control" id="distancia">
        </div>
        <div class="col-md-2 align-self-end">

            <button type="submit" class="btn btn-primary ">Enviar</button>
        </div>
    </form>



    </div>


</div>
<div class="card">
    <div class="card-header">Adicionar Marcador</div>
    <div class="card-body">
        <form id="marker-form" class="row g-3">
            <div class="col-md-3">
                <label for="marker-name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="marker-name">
            </div>
            <div class="col-md-3">
                <label for="marker-latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" id="marker-latitude">
            </div>
            <div class="col-md-3">
                <label for="marker-longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" id="marker-longitude">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Adicionar Marcador</button>
            </div>
        </form>



    </div>

    <div class="card">
        <div class="card-header">Adicionar Dados de Localização do Google</div>
        <div class="card-body">
            @csrf
            <form action="{{route('erb.show.honeycombgoogle')}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-9">
                    <label for="markers" class="form-label">Nome</label>
                    <textarea type="text" name="markers" class="form-control" id="markers"></textarea>
                </div>

                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-primary">Adicionar Marcadores</button>
                </div>
            </form>



        </div>


</div>

@push('js')
    <script>


        const map = L.map('mapid').setView([-1.38005, -48.42637], 16);
        const iconMark = L.icon({
            iconUrl: "{{asset('images/icon-point.png')}}",
            iconSize: [48, 48],
            iconAnchor: [22, 52]
        });
        const icon3g = L.icon({
            iconUrl: "{{asset('images/3g.png')}}",
            iconSize: [48, 48],
            iconAnchor: [22, 52]
        });
        const icon4g = L.icon({
            iconUrl: "{{asset('images/4g.png')}}",
            iconSize: [48, 48],
            iconAnchor: [22, 52]
        });
        const icon5g = L.icon({
            iconUrl: "{{asset('images/5g.png')}}",
            iconSize: [48, 48],
            iconAnchor: [22, 52]
        });
        const iconGps = L.icon({
            iconUrl: "{{asset('images/gps.png')}}",
            iconSize: [48, 48],
            iconAnchor: [22, 52]
        });
        const iconWifi = L.icon({
            iconUrl: "{{asset('images/wi-fi.png')}}",
            iconSize: [48, 48],
            iconAnchor: [22, 52]
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);
        var marker1 = null;
        var marker2 = null;
        var line = null;

        map.on('click', function(e) {
            if (marker1 === null) {
                marker1 = L.marker(e.latlng).addTo(map);
            } else if (marker2 === null) {
                marker2 = L.marker(e.latlng).addTo(map);
                var midpoint = getMidpoint(marker1.getLatLng(), marker2.getLatLng());
                var intersectionPoint = getIntersectionPoint(marker1.getLatLng(), marker2.getLatLng(), midpoint);

                line = L.polyline([midpoint, intersectionPoint], { color: 'red' }).addTo(map);
                extendLine(line, midpoint, intersectionPoint, 2); // Extend the line by 2 times

                // Uncomment the line below if you want to remove the markers after drawing the line
                // map.removeLayer(marker1).removeLayer(marker2);

                // Uncomment the line below if you want to remove the line after drawing it
                // map.removeLayer(line);
                marker1 = null;
                marker2 = null;
            }
        });

        function getMidpoint(point1, point2) {
            var lat = (point1.lat + point2.lat) / 2;
            var lng = (point1.lng + point2.lng) / 2;
            return L.latLng(lat, lng);
        }

        function getIntersectionPoint(point1, point2, midpoint) {
            var latDiff = point2.lat - point1.lat;
            var lngDiff = point2.lng - point1.lng;

            var intersectionLat = midpoint.lat - (lngDiff / 2);
            var intersectionLng = midpoint.lng + (latDiff / 2);

            return L.latLng(intersectionLat, intersectionLng);
        }

        function extendLine(line, startPoint, endPoint, length) {
            var latDiff = endPoint.lat - startPoint.lat;
            var lngDiff = endPoint.lng - startPoint.lng;

            var totalDistance = Math.sqrt(latDiff * latDiff + lngDiff * lngDiff);
            var ratio = length / (2 * totalDistance);

            var extendedLat = startPoint.lat + latDiff * ratio;
            var extendedLng = startPoint.lng + lngDiff * ratio;

            var extendedPoint1 = L.latLng(startPoint.lat + latDiff * (1 - ratio), startPoint.lng + lngDiff * (1 - ratio));
            var extendedPoint2 = L.latLng(extendedLat, extendedLng);

            line.setLatLngs([extendedPoint1, extendedPoint2]);
        }

        function extendLine2(line, startPoint, endPoint, angle) {
            var latDiff = endPoint.lat - startPoint.lat;
            var lngDiff = endPoint.lng - startPoint.lng;

            var cosAngle = Math.cos(angle);
            var sinAngle = Math.sin(angle);

            var extendedLat = startPoint.lat + latDiff * cosAngle - lngDiff * sinAngle;
            var extendedLng = startPoint.lng + latDiff * sinAngle + lngDiff * cosAngle;

            var extendedPoint1 = L.latLng(startPoint.lat, startPoint.lng);
            var extendedPoint2 = L.latLng(extendedLat, extendedLng);

            line.setLatLngs([extendedPoint1, extendedPoint2]);
        }

        @if(isset($array_erbs))


        @foreach($array_erbs as $erb)
        var icon;

        if ("{{$erb['tecs']}}".includes('5G')) {
            icon = icon5g;
        } else if ("{{$erb['tecs']}}".includes('4G')) {
            icon = icon4g;
        } else if ("{{$erb['tecs']}}".includes('3G')) {
            icon = icon3g;
        } else {
            // A string {{$erb['tecs']}} não contém nenhuma das substrings esperadas
            icon = iconMark;
        }

            var marker = L.marker([{{$erb['latitude']}}, {{$erb['longitude']}}], {icon: icon}).addTo(map);
        marker.bindPopup("Informação: {{$erb['tecs']}}").openPopup();
        @endforeach
        @endif

        @if(isset($markers))


        @foreach($markers as $googleMarker)
        var icon;

        switch ("{{$googleMarker['Source']}}") {
            case 'GPS':
                icon = iconGps;
                break;
            case 'GSM':
                icon = icon3g;
                break;

            case 'WIFI':
                icon = iconWifi;
                break;
            default:
                icon = iconMark;
                break;
        }
        {{--var marker = L.marker([{{$googleMarker['Latitude']}}, {{$googleMarker['Longitude']}}], {icon: icon}).addTo(map);--}}
        {{--marker.bindPopup("Informação: {{$googleMarker['Timestamp (UTC)']}}").openPopup();--}}
        var latitude = {{ $googleMarker['Latitude'] }};
        var longitude = {{ $googleMarker['Longitude'] }};
        var timestamp = "{{ $googleMarker['Timestamp (UTC)'] }}";

        var marker = L.marker([latitude, longitude], {icon: icon}).addTo(map);
        marker.bindPopup("Informação: <a href='{{ route('erb.dualmap') }}?latitude=" + latitude + "&longitude=" + longitude + "'>" + timestamp + "</a>").openPopup();
        var circle = L.circle([{{$googleMarker['Latitude']}}, {{$googleMarker['Longitude']}}], {
            color: 'red',
            fillColor: 'red',
            fillOpacity: 0.3,
            radius: {{$googleMarker['Display Radius (Meters)']}}
        }).addTo(map);
        @endforeach
        @endif




        var poligonos = [];
       @php
        if(isset($array_erbs)){
           Erb::desenharPoligonos($array_erbs);

        }
        @endphp

        // Array para armazenar referências aos polígonos criados

        // Função para criar e adicionar um polígono ao mapa e ao array de polígonos


        // Função para excluir todos os polígonos do mapa e limpar o array de polígonos
        function excluirPoligonos() {
            for (var i = 0; i < poligonos.length; i++) {
                map.removeLayer(poligonos[i]);
            }
            poligonos = []; // Limpa o array de polígonos
        }

        // Adicionar um ouvinte de eventos ao botão "Excluir Polígonos"
        document.getElementById('excluir-poligonos').addEventListener('click', function() {
            excluirPoligonos();
        });

        document.getElementById('sector-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var latitude = parseFloat(document.getElementById('latitude').value);
            var longitude = parseFloat(document.getElementById('longitude').value);
            var margem = parseFloat(document.getElementById('margem').value);
            var azimute = parseFloat(document.getElementById('azimute').value);
            var distancia = parseFloat(document.getElementById('distancia').value);

            var startLatLng = L.latLng(latitude, longitude);
            var angleStart = (azimute - margem / 2) * Math.PI / 180; // Converter azimute e margem para radianos
            var angleEnd = (azimute + margem / 2) * Math.PI / 180;

            var startPoint = calculateDestinationPoint(startLatLng, distancia, angleStart);
            var endPoint = calculateDestinationPoint(startLatLng, distancia, angleEnd);

            var triangle = L.polygon([startLatLng, startPoint, endPoint], {
                color: 'blue',
                fillColor: 'blue',
                fillOpacity: 0.3
            }).addTo(map);

            // Limpar os campos do formulário após a submissão
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            document.getElementById('margem').value = '';
            document.getElementById('azimute').value = '';
            document.getElementById('distancia').value = '';
        });

        function calculateDestinationPoint(startLatLng, distance, angle) {
            var earthRadius = 6371; // Raio médio da Terra em quilômetros
            var angularDistance = distance / earthRadius;

            var startLat = startLatLng.lat * Math.PI / 180; // Converter latitude inicial para radianos
            var startLng = startLatLng.lng * Math.PI / 180; // Converter longitude inicial para radianos

            var endLat = Math.asin(Math.sin(startLat) * Math.cos(angularDistance) +
                Math.cos(startLat) * Math.sin(angularDistance) * Math.cos(angle));

            var endLng = startLng + Math.atan2(Math.sin(angle) * Math.sin(angularDistance) * Math.cos(startLat),
                Math.cos(angularDistance) - Math.sin(startLat) * Math.sin(endLat));

            endLat = endLat * 180 / Math.PI; // Converter latitude final para graus
            endLng = endLng * 180 / Math.PI; // Converter longitude final para graus

            return L.latLng(endLat, endLng);
        }

        document.getElementById('marker-form').addEventListener('submit', function(event) {
            event.preventDefault();

            var markerName = document.getElementById('marker-name').value;
            var markerLatitude = parseFloat(document.getElementById('marker-latitude').value);
            var markerLongitude = parseFloat(document.getElementById('marker-longitude').value);

            var markerLatLng = L.latLng(markerLatitude, markerLongitude);

            // Adicionar marcador ao mapa
            var marker = L.marker(markerLatLng).addTo(map);

            // Adicionar popup com o nome do marcador
            marker.bindPopup(markerName).openPopup();

            // Limpar os campos do formulário após a submissão
            document.getElementById('marker-name').value = '';
            document.getElementById('marker-latitude').value = '';
            document.getElementById('marker-longitude').value = '';
        });



    </script>
@endpush
