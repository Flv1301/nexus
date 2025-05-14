@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@php
    use App\Libs\DMSConvert;
    use App\Models\Gi2;

@endphp




@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
   <h3>Match GI2/Relatos</h3>
@stop

@section('content')

    <div class="container-fluid">


        <div class="card">
            <div class="card-header">

                <div class="card-tools">
                    <form action="/match" method="get">
                        <div class="input-group " style="width: 300px;">

                            <input type="text" name="imei_search" class="form-control float-left" placeholder="IMEI ou IMSI">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

            <div class="card-body">



            </div>

        </div>

    </div>
    <div class="col-md-12 mb-5">

        @php
            $heads = [
                'data',
                'imei',
                'localização',
                 ['label' => 'Actions', 'no-export' => true, 'width' => 5]
            ];




            $config = [

                'order' => [[2, 'asc']],
                'columns' => [null, null, ['orderable' => true]],
            ];
        @endphp

        <x-adminlte-datatable  id="table2" :heads="$heads" head-theme="dark" :config="$config"
                               striped hoverable bordered compressed>

            <tbody>
            @foreach($gi2 as $gi)
                @php
                    $btnDetails = "<a href='" . route('imei', ['imei_search' => $gi->imei]) ."'
                                         class='btn btn-sm mx-1' title='abrir relato'>
                                           <i class='fa fa-lg fa-fw fa-eye'></i>
                                         </a>";

                @endphp

                <tr>
                    <td><?php
                            $date = new DateTimeImmutable($gi->date_time);
                            $lat = DMSConvert::myConversion($gi->latitude);
                            $long = DMSConvert::myConversion($gi->longitude);
                            echo $date->format('d/m/Y H:i:s');


                            ?></td>

                    <td>{{$gi->imei}}</td>
                    <td> <nobr>  <a target="_blank" class="btn btn-dark btn btn-sm mx-1" href="https://www.google.com/maps/search/?api=1&query={{$lat}}%2C{{$long}}">
                                Abrir</a> </nobr></td>


                    <td> {!! '<nobr>'.$btnDetails !!}</td>
                </tr>

            @endforeach

            </tbody>
        </x-adminlte-datatable>




    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop
