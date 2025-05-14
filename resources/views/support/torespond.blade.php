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
@section('plugins.Select2', true)
<x-page-header title="Responder a Solicitação de Suporte - {{$support->title}}">
    <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
       type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('support.response', $support)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @include('support.form_respond')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
