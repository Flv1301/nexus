@php use App\Helpers\FeatureHelper; @endphp
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>

@stop

@section('content_header')
    <div class="card card-dark" style="margin-bottom: 0; !important;">
        <div class="card-header">
            <div class="d-flex">
                <div class="mr-auto">
                    <h1 class="h1">Localização Gi2</h1>
                </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{$gi2['latitude']}}%2C{{$gi2['longitude']}}" class="btn btn-primary float-right right d-inline">Abrir no Maps</a>
            </div>
        </div>
        @endsection

@section('content')
    <div id="map" style="width: 100%; height: 600px;">


    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
          integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
          crossorigin=""/>

@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
            integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
            crossorigin=""></script>
    <script>
        // create a map in the "map" div, set the view to a given place and zoom
        <?php







        ?>
        var map = L.map('map').setView([ {{$gi2['latitude']}},{{$gi2['longitude']}}], 16);

        // add an OpenStreetMap tile layer
        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        <?php

                    $strCircle = "var circle  = L.circle([";
                    $strCircle.= $gi2['latitude'] . ", " . $gi2['longitude']. "]";
                    $strCircle .= ", { color: 'red', fillColor: '#f03', fillOpacity: 0.5, radius: 500}).addTo(map);";
                    echo $strCircle;
        ?>
    </script>
@stop
