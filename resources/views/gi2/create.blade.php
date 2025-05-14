@php


@endphp
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h3>Gi2</h3>
@stop

@section('content')
    <div class="row">

        <!-- /.col-md-6 -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">

                    </div>
                </div>
                <div class="card-body">

                    <!-- /.d-flex -->



                    <div class="row">



                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Upload Gi2</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form action="save" method="post"  enctype="multipart/form-data">
                                @csrf
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label for="arquivo">Arquivo CSV</label>

                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input  type="file" class="custom-file-input"  name="arquivo">
                                                    <label class="custom-file-label" for="arquivo">escolha o arquivo</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <input type="submit" class="btn-primary" value="Enviar">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- /.card -->


        </div>
        <!-- /.col-md-6 -->



    </div>


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
