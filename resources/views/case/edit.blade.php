@extends('adminlte::page')
@section('title',"Caso " . $case->name)
@section('plugins.Select2', true)
@section('plugins.BsCustomFileinput', true)
@section('plugins.BootstrapSelect', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Summernote', true)
<x-page-header title="Edição do Caso {{\Illuminate\Support\Str::title($case->name)}}">
    <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
       type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card card-warning card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a href="#tab_data" data-toggle="pill" aria-controls="tab_data" role="tab"
                       class="nav-link active">Dados</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_person" data-toggle="pill" aria-controls="tab_sharing" role="tab"
                       class="nav-link">Alvos</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_sharing" data-toggle="pill" aria-controls="tab_sharing" role="tab"
                       class="nav-link">Compartilhamento</a>
                </li>
            </ul>
        </div>
        <form action="{{ route('case.update', $case) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_data">
                        @include('case.store.data')
                    </div>
                    <div class="tab-pane" id="tab_person" role="tabpanel">
                        @include('case.store.person')
                    </div>
                    <div class="tab-pane" id="tab_sharing" role="tabpanel">
                        @include('case.store.sharing')
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <x-adminlte-button type="submit" label="Atualizar"
                                   theme="success"
                                   icon="fas fa-lg fa-save"/>
            </div>
        </form>
    </div>
@endsection
