{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Documento')
@section('content')
    <div class="card col-md-12" style="margin-top: 4em !important;">
        <div class="card-body">
            <object class="col-md-12 vh-100" data="data:{{$mime}};base64,{{base64_encode($read)}}" type="{{$mime}}">
                <span class="text-black text-lg">Ooops! O navegador não suporta esse tipo de arquivo.</span>
            </object>
        </div>
        <div class="card-footer">
            <span class="text-info">Download</span><a href="{{route('download', $href)}}"><i
                    class="fa fa-lg fa-download pl-2"></i></a>
        </div>
    </div>

@endsection
