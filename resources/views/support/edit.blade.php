{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Solicitação de Suporte')
@section('plugins.Summernote', true)
@section('plugins.Sweetalert2', true)
@section('plugins.BsCustomFileinput', true)
<x-page-header title="Atualização de Solicitação de Suporte"/>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('support.update', $support)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('support.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
