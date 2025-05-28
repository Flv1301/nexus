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
                                    @can('nexus')
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input" type="checkbox" id="nexus-checked"
                                               value="nexus" name="options[]"
                                               @if(in_array('nexus', $request->options) || empty($request->options)) checked @endif />
                                        <label for="nexus-checked" class="custom-control-label">Nexus</label>
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
    <script src="{{ asset('js/cpf-mask.js') }}"></script>
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

