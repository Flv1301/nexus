@php
    use App\Models\LetterCompany;
@endphp
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="mb-3">Gerador de Ofícios</h1>

@stop

@section('content')
    <div class="row">

        <div class="col-md-12">

            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Gerar Ofício</h3>
                </div>


                @csrf
                <form method="post" action="{{ route('letter.generate') }}">
                    @csrf
                    <div class="card-body">

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label for="number" class="input-group-text">Número do Ofício</label>
                            </div>
                                <input type="number" id="number" name="number" class="form-control">
                        </div>



                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <label for="whatyouhave" class="input-group-text">Solicitar dados cadastrais Vinculados à:</label>
                            </div>
                            <select id="whatyouhave" name="whatyouhave" class="form-control">
                                <option value="CPF">CPF</option>
                                <option value="Nome">Nome</option>
                                <option value="Email">Email</option>
                                <option value="Telefone">Telefone</option>
                                <option value="Imei">IMEI</option>
                                <option value="Numero de Serie">Nº de Serie</option>
                                <option value="usuario">Usuário</option>
                                <option value="IP">IP</option>
                                <option value="boleto">Boleto</option>
                                <option value="Placa de Veículo">Placa de Veículo</option>
                            </select>
                        </div>
                        <textarea id="summernote-editor" name="content" class="form-control"></textarea>

                        @php
                            $companys = LetterCompany::all();

                            foreach ($companys as $company) {


                        @endphp
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="companysIds[]"
                                   id="company{{$company->id}}" value="{{$company->id}}">
                            <label class="form-check-label" for="company{{$company->id}}">{{$company->nome}}</label>
                        </div>

                        @php
                            }
                        @endphp


                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>


        </div>
        @stop

        @section('css')
            <link rel="stylesheet" href="/css/admin_custom.css">
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.css" rel="stylesheet">

        @stop

        @section('js')
            <script> console.log('Hi!'); </script>

            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.js"></script>
            <script>
                $(document).ready(function() {
                    $('#summernote-editor').summernote();
                });
            </script>
@stop
