{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 21/06/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Pesquisa de Veículo')
@section('plugins.Sweetalert2', true)
<x-page-header title="Pesquisa de Veículo"></x-page-header>
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <form action="{{route('search.vehicle.plate')}}" method="GET" id="form-search">
                        <div class="input-group input-group-md">
                            <input type="text" name="plate" class="form-control" style="text-transform: uppercase;"
                                   id="plate"
                                   placeholder="Placa" maxlength="7" minlength="7">
                            <div class="input-group-btn ml-2">
                                <x-adminlte-button type="submit" icon="fas fa-search" theme="secondary"
                                                   label="Pesquisar"/>
                            </div>
                            <div class="ml-3">
                                <x-loading/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="result-content"></div>
@endsection
@push('js')
    <script>
        const toggleLoading = (active) => {
            const loading = document.getElementById('loading');
            loading.classList.toggle('d-none', !active);
        };

        const form = document.getElementById('form-search');
        const action = form.getAttribute('action');
        const resultContent = document.getElementById('result-content');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            toggleLoading(true);
            resultContent.innerHTML = '';

            try {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData).toString();
                const apiUrl = action + '?' + params;

                const response = await fetch(apiUrl, {
                    method: 'GET',
                });
                const dataResponse = await response.json();

                if (response.ok) {
                    resultContent.innerHTML = dataResponse.view;
                    initMap(dataResponse.data.moviments);
                } else {
                    alert('Não foi possível localizar os dados!');
                }
            } catch (error) {
                alert('Falha na comunicação com o servidor!');
            } finally {
                toggleLoading(false);
            }
        });

        function alert(message) {
            Swal.fire({
                position: 'top-end',
                icon: 'info',
                title: message,
                showConfirmButton: false,
                timer: 3000
            })
        }
    </script>
    <script>
        function initMap(mapData) {
            if (!Array.isArray(mapData) || mapData.length === 0) {
                return;
            }

            const firstMovement = mapData[0];
            const mapCenter = {lat: firstMovement.latitude, lng: firstMovement.longitude};
            const icon = "https://cdn-icons-png.flaticon.com/128/3097/3097144.png";

            const map = new google.maps.Map(document.getElementById('map_view'), {
                center: mapCenter,
                zoom: 10
            });

            const infowindow = new google.maps.InfoWindow();

            mapData.forEach(moviment => {
                const position = {lat: moviment.latitude, lng: moviment.longitude};
                const title = moviment.local;
                const marker = createMarker(map, position, title, icon);

                addMarkerClickListener(marker, map, title);

                if (moviment !== firstMovement) {
                    const path = [position, {lat: firstMovement.latitude, lng: firstMovement.longitude}];
                    createPolyline(map, path);
                }
            });
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

        function addMarkerClickListener(marker, map, title) {
            marker.addListener('click', function () {
                const infowindow = new google.maps.InfoWindow({
                    content: title
                });
                infowindow.open(map, marker);
            });
        }

        function createPolyline(map, path) {
            const polyline = new google.maps.Polyline({
                path: path,
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

            polyline.setMap(map);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&callback=initMap" async defer>
@endpush
