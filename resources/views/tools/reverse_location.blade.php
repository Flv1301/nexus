{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/10/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Reverse Location')
<x-page-header title="Reverse Location">
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reverse-location-map') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <!-- Campo 1 -->
                        <div class="mb-3">
                            <label for="file1" class="form-label">Arquivo 1:</label>
                            <input type="file" class="form-control" id="file1" name="file1">
                        </div>

                        <!-- Campo 2 -->
                        <div class="mb-3">
                            <label for="file2" class="form-label">Arquivo 2:</label>
                            <input type="file" class="form-control" id="file2" name="file2">
                        </div>

                        <!-- Campo 3 -->
                        <div class="mb-3">
                            <label for="file3" class="form-label">Arquivo 3:</label>
                            <input type="file" class="form-control" id="file3" name="file3">
                        </div>



                        <!-- Botão de Enviar -->
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection
