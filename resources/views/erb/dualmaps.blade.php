@extends('adminlte::page')
@section('title', 'GI2')
@section('plugins.Datatables', true)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div id="map-google" style="height: 400px;"></div>
            </div>
            <div class="col-md-4">
                <div id="map-satellite" style="height: 400px;"></div>
            </div>
            <div class="col-md-4">
                <div id="street-view" style="height: 400px;"></div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var latitude = {{ $latitude }};
            var longitude = {{ $longitude }};

            var googleMap = new google.maps.Map(document.getElementById('map-google'), {
                center: { lat: latitude, lng: longitude },
                zoom: 20,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            var satelliteMap = new google.maps.Map(document.getElementById('map-satellite'), {
                center: { lat: latitude, lng: longitude },
                zoom: 20,
                mapTypeId: google.maps.MapTypeId.SATELLITE
            });

            var streetView = new google.maps.StreetViewPanorama(
                document.getElementById('street-view'),
                {
                    position: { lat: latitude, lng: longitude },
                    pov: { heading: 165, pitch: 0 },
                    zoom: 1
                }
            );

            // Adiciona evento de clique no mapa comum
            googleMap.addListener('click', function(e) {
                var latLng = e.latLng;
                satelliteMap.setCenter(latLng);
                streetView.setPosition(latLng);
            });

            // Adiciona evento de clique no mapa via satélite
            satelliteMap.addListener('click', function(e) {
                var latLng = e.latLng;
                googleMap.setCenter(latLng);
                streetView.setPosition(latLng);
            });

            // Adiciona evento de clique no street view
            streetView.addListener('position_changed', function() {
                var position = streetView.getPosition();
                googleMap.setCenter(position);
                satelliteMap.setCenter(position);
            });
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbwOstDAxBahRAO9ZpdasQtaXa2dQV5GA"></script>
@endpush
