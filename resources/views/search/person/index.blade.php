{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('title','Pesquisa de Pessoa')
<x-page-header title="Pesquisa De Pessoa">
    <div>
        <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
           type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
    </div>
</x-page-header>
@section('content')
    @include('sweetalert::alert')
    <div class="row">
        <div class="col-md-4">
            <form action="{{route('person.search')}}" method="post" id="form-search">
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
                                    @can('hydra')
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="hydra-checked"
                                               value="person" name="options[]"
                                               @if(in_array('person', $request->options) || empty($request->options)) checked @endif />
                                        <label for="hydra-checked" class="custom-control-label">Hydra</label>
                                    </div>
                                    @endcan
                                    @can('cortex')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="cortex-checked"
                                                   value="cortex" name="options[]"
                                                   @if(in_array('cortex', $request->options) || empty($request->options)) checked @endif />
                                            <label for="cortex-checked" class="custom-control-label">Cortex</label>
                                        </div>
                                    @endcan
                                    @can('cortex')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="bnmp-checked"
                                                   value="bnmp" name="options[]"
                                                   @if(in_array('bnmp', $request->options) || empty($request->options)) checked @endif />
                                            <label for="bnmp-checked" class="custom-control-label">Bnmp</label>
                                        </div>
                                    @endcan
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
                                    @can('galton')
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="galton-checked"
                                                   value="galton" name="options[]"
                                                   @if(in_array('galton', $request->options) || empty($request->options)) checked @endif />
                                            <label for="galton-checked" class="custom-control-label">Galton</label>
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
                                    @can('prodepa')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="prodepa-checked"
                                                   value="prodepa" name="options[]"
                                                   @if(in_array('prodepa', $request->options) || empty($request->options)) checked @endif />
                                            <label for="prodepa-checked" class="custom-control-label">Prodepa</label>
                                        </div>
                                    @endcan
                                    @can('seduc')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="seduc-checked"
                                                   value="seduc" name="options[]"
                                                   @if(in_array('seduc', $request->options) || empty($request->options)) checked @endif />
                                            <label for="seduc-checked" class="custom-control-label">Seduc</label>
                                        </div>
                                    @endcan
                                    @can('polinter')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="polinter-checked"
                                                   value="polinter" name="options[]"
                                                   @if(in_array('polinter', $request->options) || empty($request->options)) checked @endif />
                                            <label for="polinter-checked" class="custom-control-label">Polinter</label>
                                        </div>
                                    @endcan
                                    @can('equatorial')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="equatorial-checked"
                                                   value="equatorial" name="options[]"
                                                   @if(in_array('equatorial', $request->options) || empty($request->options)) checked @endif />
                                            <label for="equatorial-checked" class="custom-control-label">Equatorial</label>
                                        </div>
                                    @endcan
                                    @can('cacador')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox"
                                                   id="cacador-rr-checked"
                                                   value="cacador" name="options[]"
                                                   @if(in_array('cacador', $request->options) || empty($request->options)) checked @endif />
                                            <label for="cacador-rr-checked" class="custom-control-label">Caçador-RR</label>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <label id="checkbox-mark" class="text-info">Desmarcar Todos</label>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <x-adminlte-input name="name" placeholder="Nome ou Alcunha" label="Nome ou Alcunha"
                                                          value="{{old('name') ?? $request->name ?? ''}}"/>
                                    </div>
                                    <div class="form-group">
                                        <x-adminlte-input name="lastname" placeholder="Sobrenome"
                                                          label="Sobrenome"
                                                          value="{{old('sobrenome') ?? $request->lastname ?? ''}}"/>
                                    </div>
                                    <div class="form-group">
                                        <x-adminlte-input name="cpf" placeholder="CPF" class="mask-cpf-number"
                                                          label="CPF"
                                                          value="{{old('cpf') ?? $request->cpf ?? ''}}"/>
                                    </div>
                                    <div class="form-group">
                                        <x-adminlte-input name="rg" placeholder="RG" label="RG"
                                                          value="{{old('rg') ?? $request->rg ?? ''}}"/>
                                    </div>
                                    <div class="form-group">
                                        <x-adminlte-input name="mother" placeholder="Nome da Mãe"
                                                          label="Genitora"
                                                          value="{{old('mother') ?? $request->mother ?? ''}}"/>
                                    </div>
                                    <div class="form-group">
                                        <x-adminlte-input name="father" placeholder="Nome do Pai"
                                                          label="Genitor"
                                                          value="{{old('father') ?? $request->father ?? ''}}"/>
                                    </div>
                                    <div class="form-group">
                                        @php $config = ['format' => 'DD/MM/YYYY']; @endphp
                                        <x-adminlte-input-date name="birth_date"
                                                               class="mask-date"
                                                               id="birth_date"
                                                               :config="$config"
                                                               placeholder="Data Nascimento"
                                                               label="Data Nascimento"
                                                               value="{{old('birth_date') ?? $request->birth_date ?? ''}}">
                                            <x-slot name="appendSlot">
                                                <div class="input-group-text bg-gradient-warning">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input-date>
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
                                        <span>Critérios de pesquisa por base</span>
                                        <span>* Cortex - CPF ou Nome e Nome da Mãe ou Nome e Data Nascimento</span>
                                        <span>* Prodepa - RG ou Nome e Sobrenome e Data Nascimento</span>
                                        <span>* Bnmp - CPF ou Nome e Nome da Mãe ou Nome e Data Nascimento</span>
                                        <span>* Seduc - Nome, Nome da Mãe, Nome do Pai</span>
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
                @include('search.person.list')
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

