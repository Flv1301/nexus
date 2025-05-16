@extends('adminlte::page')
@section('title','VCard')
@section('plugins.Datatables', true)
<x-page-header title="Pesquisa de Contatos VCard">
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <form action="{{route('vcard.search.phone')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <x-adminlte-input type="text" name="number_or_name" class="form-control" id="number_or_name"
                                          placeholder="Pesquise por número, nome ou contato"
                                          value="{{old('number_or_name')}}"
                                          required/>
                    </div>
                    <div class="ml-2">
                        <x-adminlte-button
                            type="submit"
                            icon="fas fa-search"
                            theme="secondary"
                            label="Pesquisar"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
@include('vcard.list')
@endsection
