{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 24/07/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('title','Pesquisa de Telefone')
<x-page-header title="Pesquisa De Telefone">
    <div>
        <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
           type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
    </div>
</x-page-header>
@section('content')
    @include('sweetalert::alert')
    <div class="row">
        <div class="col-md-4">
            <form action="{{route('telephone.search')}}" method="GET" id="form-search">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-2">
                            <div class="justify-content-end">
                                <i id="icon-bars-hidden" class="fas fa-bars"></i>
                            </div>
                        </div>
                        <div id="content-form">
                            <div class="form-row">
                                <div class="d-flex flex-wrap">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="hydra-checked"
                                               value="person" name="options[]"
                                               @if(in_array('person', $request->options) || empty($request->options)) checked @endif />
                                        <label for="hydra-checked" class="custom-control-label">Hydra</label>
                                    </div>
                                    @can('sisp')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="sisp-checked"
                                                   value="sisp" name="options[]"
                                                   @if(in_array('sisp', $request->options) || empty($request->options)) checked @endif />
                                            <label for="sisp-checked" class="custom-control-label">Sisp</label>
                                        </div>
                                    @endcan
                                    @can('seap')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="seap-checked"
                                                   value="seap" name="options[]"
                                                   @if(in_array('seap', $request->options) || empty($request->options)) checked @endif />
                                            <label for="seap-checked" class="custom-control-label">Seap</label>
                                        </div>
                                    @endcan
                                    @can('seap_visitante')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="seap-visitante-checked"
                                                   value="seap_visitante" name="options[]"
                                                   @if(in_array('seap_visitante', $request->options) || empty($request->options)) checked @endif />
                                            <label for="seap-visitante-checked" class="custom-control-label">Seap Visitante</label>
                                        </div>
                                    @endcan
                                    @can('dpa')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox" id="dpa-checked"
                                                   value="dpa" name="options[]"
                                                   @if(in_array('dpa', $request->options) || empty($request->options)) checked @endif />
                                            <label for="dpa-checked" class="custom-control-label">Dpa</label>
                                        </div>
                                    @endcan
                                    @can('srh')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox" id="srh-checked"
                                                   value="srh" name="options[]"
                                                   @if(in_array('srh', $request->options) || empty($request->options)) checked @endif />
                                            <label for="srh-checked" class="custom-control-label">Srh</label>
                                        </div>
                                    @endcan
                                    @can('equatorial')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox" id="equatorial-checked"
                                                   value="equatorial" name="options[]"
                                                   @if(in_array('equatorial', $request->options) || empty($request->options)) checked @endif />
                                            <label for="equatorial-checked" class="custom-control-label">Equatorial</label>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <label id="checkbox-mark" class="text-info">Desmarcar Todos</label>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="col-md-3">
                                            <x-adminlte-input name="ddd" placeholder="DDD" label="DDD" class="mask-ddd"
                                                              value="{{old('ddd') ?? $request->ddd ?? ''}}"/>
                                        </div>
                                        <div class="col-md-8">
                                            <x-adminlte-input name="telephone" placeholder="Telefone (Apenas números)"
                                                              class="mask-number-phone"
                                                              label="Telefone*"
                                                              value="{{old('telephone') ?? $request->telephone ?? ''}}"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <label id="inputs-reset" class="text-info">Limpar Campos</label>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between">
                                            <x-adminlte-button type="submit" icon="fas fa-search"
                                                               label="Pesquisar"
                                                               theme="secondary"/>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column col-md-12 text-sm text-info mt-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="content-result" class="col-md-8">
            @isset($base)
                @include('search.person.' . $base)
            @endisset
            @isset($bases)
                @include('search.telephone.list')
            @endisset
        </div>
    </div>
@endsection
@push('js')
    <script>
        const form = document.getElementById('form-search');
        const button = document.getElementById('icon-bars-hidden');
        const contentform = document.getElementById('content-form');
        const contentresult = document.getElementById('content-result');

        button.addEventListener('click', function () {
            if (contentform.style.display === 'none') {
                contentform.style.display = 'block';
                contentresult.classList.remove('col-md-11');
                contentresult.classList.add('col-md-8');
            } else {
                contentform.style.display = 'none';
                contentresult.classList.remove('col-md-8');
                contentresult.classList.add('col-md-11');
            }
        });

        const checkboxMarks = document.getElementById('checkbox-mark');
        checkboxMarks.addEventListener('click', function () {
            if (checkboxMarks.textContent === 'Desmarcar Todos') {
                document.querySelectorAll('input[type="checkbox"]').forEach(input => input.checked = false);
                checkboxMarks.textContent = 'Marcar Todos';
            } else {
                document.querySelectorAll('input[type="checkbox"]').forEach(input => input.checked = true);
                checkboxMarks.textContent = 'Desmarcar Todos';
            }
        });

        const inputsReset = document.getElementById('inputs-reset');
        inputsReset.addEventListener('click', function () {
            form.querySelectorAll('input').forEach(input => {
                if (input.name !== '_token' && input.type !== 'checkbox') {
                    input.value = '';
                }
            });
        });
    </script>
@endpush

