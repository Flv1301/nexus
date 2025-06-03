@extends('adminlte::page')
@section('plugins.BsCustomFileinput', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)
@section('plugins.Summernote', true)
@section('plugins.KrajeeFileinput', true)
@section('title','Cadastro de Pessoa')

@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<x-page-header title="Cadastro de Pessoa">
    <div>
        <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
           type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
    </div>
</x-page-header>
@section('content')
    @php $config = ['format' => 'DD/MM/YYYY']; @endphp
    @include('person.navbar')
    <form action="{{ route('person.store') }}" method="post"
          enctype="multipart/form-data" id="form">
        @csrf
        <div class="card-body">
            <x-page-messages />
            <div class="tab-content">
                @include('person.content')
            </div>
        </div>
        <div class="card-footer">
            <x-adminlte-button type="submit" label="Cadastrar"
                               theme="success"
                               icon="fas fa-lg fa-save"/>
        </div>
    </form>
    </div>
@endsection

@push('js')
<script src="{{ asset('js/uppercase-mask.js') }}"></script>
<script src="{{ asset('js/cpf-mask.js') }}"></script>
<script src="{{ asset('js/dynamic-cities.js') }}"></script>
@endpush

