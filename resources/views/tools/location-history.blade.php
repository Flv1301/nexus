{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/10/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Location')
<x-page-header title="Location History Google">
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('location-history-map') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="file1" class="form-label">Histórico de Localização:</label>
                            <input type="file" class="form-control" id="file1" name="file1">
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Data de Início:</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">Data de Término:</label>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="gps" checked name="gps">
                            <label class="form-check-label"  for="gps">GPS</label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="wifi" checked name="wifi">
                            <label class="form-check-label" for="wifi">WIFI</label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="cell" checked name="cell">
                            <label class="form-check-label" for="cell">CELL</label>
                        </div>

                        <div class="mb-3">
                            <label for="distance" class="form-label">Distância Máxima:</label>
                            <input type="number" class="form-control" value="50" id="distance" name="distance">
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>


            </form>
        </div>
    </div>



@endsection
