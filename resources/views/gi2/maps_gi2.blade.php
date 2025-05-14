@php
    use App\Helpers\MapsHerlper;
    use App\Models\Gi2;
@endphp
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css"/>
@endpush
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
@endpush
<div id="mapid" style="height: 600px;"></div>
@push('js')
    <script>
        const latitude = {{MapsHerlper::coordinateConverterGi2((string)$gi2->latitude)}};
        const longitude = {{MapsHerlper::coordinateConverterGi2((string)$gi2->longitude)}};
        const map = L.map('mapid').setView([latitude, longitude], 16);
        const iconMark = L.icon({
            iconUrl: "{{asset('images/icon-point.png')}}",
            iconSize: [48, 48],
            iconAnchor: [22, 52]
        });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(map);
        L.marker([latitude, longitude], {icon: iconMark}).addTo(map);
        L.circle([latitude, longitude], {
            color: '#00ff00',
            fillColor: '#00ff00',
            fillOpacity: 0.1,
            radius: 500
        }).addTo(map);
        const radius = 500;
        const color1 = '#ff0000'; // Vermelho para os primeiros 250 metros
        const fillColor1 = '#ff0000'; // Vermelho para os primeiros 250 metros
        const color2 = '#00ff00'; // Verde para os próximos 250 metros
        const fillColor2 = '#00ff00'; // Verde para os próximos 250 metros
        const fillOpacity = 0.5;
        const distancia = {{Gi2::tratamentoDeDistancia($gi2->distancia)}};
        L.circle([latitude, longitude], {
            color: color1,
            fillColor: fillColor1,
            fillOpacity: fillOpacity,
            radius: Math.min(radius, distancia) // Limita o raio ao máximo de 250 metros
        }).addTo(map);
    </script>
@endpush
