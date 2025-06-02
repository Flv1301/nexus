@extends('adminlte::page')
@section('plugins.BsCustomFileinput', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true)
@section('plugins.Summernote', true)
@section('plugins.KrajeeFileinput', true)
@section('plugins.Sweetalert2', true)
@section('title','Atualização de Pessoa')
<x-page-header title="Atualização de Pessoa">
    @if($person->active_orcrim && !auth()->user()->can('sisfac'))
        <span class="text-danger text-lg rounded mr-xl-5">
                Algumas informações são restritas. Para mais informações, entrar em contato com o suporte.
            </span>
    @endif
    <div class="ms-3 ml-xl-5">
        <a href="{{ url()->previous() }}" id="history" class="btn btn-info" type="button">
            <i class="fas fa-sm fa-backward p-1"></i>Voltar
        </a>
    </div>
</x-page-header>
@section('content')
    @php
        $config = ['format' => 'DD/MM/YYYY'];
    @endphp
    @include('person.navbar')
    <form action="{{ route('person.update', $person) }}" method="post"
          enctype="multipart/form-data" id="form">
        @csrf
        @method('PUT')
        <div class="card-body">
            <x-page-messages/>
            <div class="tab-content">
                @include('person.content')
            </div>
        </div>
        <div class="card-footer">
            <x-adminlte-button type="submit" label="Atualizar"
                               theme="success"
                               icon="fas fa-lg fa-save"
                               :disabled="$person->active_orcrim && !auth()->user()->can('sisfac')"
            />
        </div>
    </form>
    </div>
@endsection

@push('js')
<script src="{{ asset('js/uppercase-mask.js') }}"></script>
<script src="{{ asset('js/cpf-mask.js') }}"></script>
@endpush

