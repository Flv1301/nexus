@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('title','Pesquisa de Pessoa')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/dynamic-search-fields.css') }}">
@endpush

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
                                    @can('nexus')
                                        <div class="custom-control custom-checkbox ml-1">
                                            <input class="custom-control-input" type="checkbox" id="faccionado-checked"
                                                   value="faccionado" name="options[]"
                                                   @if(in_array('faccionado', $request->options)) checked @endif />
                                            <label for="faccionado-checked" class="custom-control-label">Faccionado</label>
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
                            
                            <!-- Área de Campos Dinâmicos -->
                            <div class="card">
                                <div class="card-body">
                                    <!-- Campos selecionados aparecerão aqui -->
                                    <div id="selected-fields">
                                        <!-- Os campos serão adicionados dinamicamente aqui -->
                                    </div>
                                    
                                    <!-- Seletor de Campo -->
                                    <div class="form-group">
                                        <label><i class="fas fa-plus-circle text-primary"></i> Selecionar Campo</label>
                                        <select class="form-control" id="field-selector">
                                            <option value="">Escolha um campo...</option>
                                            
                                            <!-- Dados Pessoais -->
                                            <optgroup label="📄 Dados Pessoais">
                                                <option value="name">👤 Nome ou Alcunha</option>
                                                <option value="cpf">🆔 CPF</option>
                                                <option value="rg">🎫 RG</option>
                                                <option value="mother">👩 Nome da Mãe</option>
                                                <option value="father">👨 Nome do Pai</option>
                                                <option value="birth_date">📅 Data de Nascimento</option>
                                                <option value="birth_city">🏙️ Município de Nascimento</option>
                                                <option value="tattoo">👁️ Tatuagem</option>
                                                <option value="orcrim">👥 Orcrim</option>
                                                <option value="area_atuacao">🗺️ Área de Atuação</option>
                                            </optgroup>
                                            
                                            <!-- Endereços -->
                                            <optgroup label="🏠 Endereços">
                                                <option value="city">🏢 Cidade</option>
                                            </optgroup>
                                            
                                            <!-- Contatos -->
                                            <optgroup label="📞 Contatos">
                                                <option value="phone">📱 Telefone</option>
                                            </optgroup>
                                            
                                            <!-- Redes Sociais -->
                                            <optgroup label="📧 Social">
                                                <option value="email">✉️ E-mail</option>
                                            </optgroup>
                                            
                                            <!-- Infopen -->
                                            <optgroup label="🏛️ Infopen">
                                                <option value="matricula">🎟️ Matrícula</option>
                                            </optgroup>
                                            
                                            <!-- Veículos -->
                                            <optgroup label="🚗 Veículos">
                                                <option value="placa">🚙 Placa</option>
                                            </optgroup>
                                            
                                            <!-- Antecedentes -->
                                            <optgroup label="📋 Antecedentes">
                                                <option value="bo">📄 BO</option>
                                            </optgroup>
                                            
                                            <!-- Processos -->
                                            <optgroup label="⚖️ Processos">
                                                <option value="processo">🏛️ Processo</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <button type="button" id="remove-all-fields" class="btn btn-link text-danger p-0" style="text-decoration: none;">
                                            <i class="fas fa-trash"></i> Remover Todos os Campos
                                        </button>
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
    <script src="{{ asset('js/dynamic-search-fields.js') }}"></script>
    <script src="{{ asset('js/debug-test.js') }}"></script>
    <script src="{{ asset('js/test-backend-integration.js') }}"></script>
    <script>
        // Configuração básica da interface (não relacionada aos campos dinâmicos)
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('icon-bars-hidden');
            const contentform = document.getElementById('content-form');
            const contentresult = document.getElementById('content-result');
            const checkboxMarks = document.getElementById('checkbox-mark');

            // Toggle do formulário
            if (button) {
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
            }

            // Toggle dos checkboxes das bases de dados
            if (checkboxMarks) {
                checkboxMarks.addEventListener('click', function () {
                    if (checkboxMarks.textContent === 'Desmarcar Todos') {
                        document.querySelectorAll('input[type="checkbox"]').forEach(input => input.checked = false);
                        checkboxMarks.textContent = 'Marcar Todos';
                    } else {
                        document.querySelectorAll('input[type="checkbox"]').forEach(input => input.checked = true);
                        checkboxMarks.textContent = 'Desmarcar Todos';
                    }
                });
            }

            // Carregar campos existentes da requisição após inicialização do DynamicSearchFields
            setTimeout(() => {
                @if(isset($request))
                    const existingData = {
                        @if(old('name') || (isset($request->name) && $request->name))
                            name: '{{ old("name") ?? $request->name ?? "" }}',
                        @endif
                        @if(old('cpf') || (isset($request->cpf) && $request->cpf))
                            cpf: '{{ old("cpf") ?? $request->cpf ?? "" }}',
                        @endif
                        @if(old('rg') || (isset($request->rg) && $request->rg))
                            rg: '{{ old("rg") ?? $request->rg ?? "" }}',
                        @endif
                        @if(old('mother') || (isset($request->mother) && $request->mother))
                            mother: '{{ old("mother") ?? $request->mother ?? "" }}',
                        @endif
                        @if(old('father') || (isset($request->father) && $request->father))
                            father: '{{ old("father") ?? $request->father ?? "" }}',
                        @endif
                        @if(old('birth_date') || (isset($request->birth_date) && $request->birth_date))
                            birth_date: '{{ old("birth_date") ?? $request->birth_date ?? "" }}',
                        @endif
                        @if(old('birth_city') || (isset($request->birth_city) && $request->birth_city))
                            birth_city: '{{ old("birth_city") ?? $request->birth_city ?? "" }}',
                        @endif
                        @if(old('tattoo') || (isset($request->tattoo) && $request->tattoo))
                            tattoo: '{{ old("tattoo") ?? $request->tattoo ?? "" }}',
                        @endif
                        @if(old('orcrim') || (isset($request->orcrim) && $request->orcrim))
                            orcrim: '{{ old("orcrim") ?? $request->orcrim ?? "" }}',
                        @endif
                        @if(old('area_atuacao') || (isset($request->area_atuacao) && $request->area_atuacao))
                            area_atuacao: '{{ old("area_atuacao") ?? $request->area_atuacao ?? "" }}',
                        @endif
                        @if(old('city') || (isset($request->city) && $request->city))
                            city: '{{ old("city") ?? $request->city ?? "" }}',
                        @endif
                        @if(old('phone') || (isset($request->phone) && $request->phone))
                            phone: '{{ old("phone") ?? $request->phone ?? "" }}',
                        @endif
                        @if(old('email') || (isset($request->email) && $request->email))
                            email: '{{ old("email") ?? $request->email ?? "" }}',
                        @endif
                        @if(old('matricula') || (isset($request->matricula) && $request->matricula))
                            matricula: '{{ old("matricula") ?? $request->matricula ?? "" }}',
                        @endif
                        @if(old('placa') || (isset($request->placa) && $request->placa))
                            placa: '{{ old("placa") ?? $request->placa ?? "" }}',
                        @endif
                        @if(old('bo') || (isset($request->bo) && $request->bo))
                            bo: '{{ old("bo") ?? $request->bo ?? "" }}',
                        @endif
                        @if(old('processo') || (isset($request->processo) && $request->processo))
                            processo: '{{ old("processo") ?? $request->processo ?? "" }}',
                        @endif
                    };
                    
                    if (window.dynamicFields && Object.keys(existingData).length > 0) {
                        window.dynamicFields.loadExistingFields(existingData);
                        console.log('Campos existentes carregados:', existingData);
                    }
                @endif
            }, 100); // Aguarda 100ms para garantir que o DynamicSearchFields foi inicializado
        });
    </script>
@endpush

