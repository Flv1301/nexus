@can('seap_monitoramento')
    <!DOCTYPE html>
<html>
<head>
    <title>SEAP - Monitorado</title>
</head>
<body>
<div id="map" style="height: 1080px;"></div>
</body>

</html>
<script>
    function initMap() {
        const response = @json($response);
        const mapData = response.features[0];
        const circleData = response.features[1];
        const mapCenter = extractCoordinates(mapData.geometry.coordinates);
        const circleCenter = extractCoordinates(circleData.geometry.coordinates[0].reverse());

        const iconHouse = "https://cdn-icons-png.flaticon.com/128/3293/3293413.png";
        const iconPerson = "https://cdn-icons-png.flaticon.com/128/11195/11195116.png";
        const map = new google.maps.Map(document.getElementById('map'), {
            center: mapCenter,
            zoom: 16
        });

        const infowindow = new google.maps.InfoWindow();
        const circleMarker = createMarker(map, circleCenter, circleData.properties.descricao, iconHouse);
        const mapMarker = createMarker(map, mapCenter, mapData.properties.nome, iconPerson);
        // const circleOptions = {
        //     strokeColor: '#FF0000',
        //     strokeOpacity: 0.8,
        //     strokeWeight: 2,
        //     fillColor: '#FF0000',
        //     fillOpacity: 0.35,
        //     map: map,
        //     center: circleCenter,
        //     radius: 100
        // };
        // const circle = new google.maps.Circle(circleOptions);
        const polyline = new google.maps.Polyline({
            path: [mapCenter, circleCenter],
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });

        polyline.setMap(map);
        addMarkerClickListener(mapMarker, map, infowindow, mapData.properties.nome);
        addMarkerClickListener(circleMarker, map, infowindow, circleData.properties.descricao);
    }

    function extractCoordinates(coordinates) {
        return {
            lat: coordinates[0],
            lng: coordinates[1]
        };
    }

    function createMarker(map, position, title, iconUrl = "") {
        const icon = {
            url: iconUrl,
            scaledSize: new google.maps.Size(32, 32)
        };
        return new google.maps.Marker({
            position: position,
            map: map,
            title: title,
            icon: icon
        });
    }

    function addMarkerClickListener(marker, map, infowindow, content) {
        marker.addListener('click', function () {
            infowindow.setContent(content);
            infowindow.open(map, marker);
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap" async defer>
</script>
@endcan
