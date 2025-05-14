{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title', 'GI2')
@section('plugins.Datatables', true)
<x-page-header title="GI2">
    <div>
        <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
           type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
    </div>
</x-page-header>
@section('content')
    @include('gi2.content')
@endsection
