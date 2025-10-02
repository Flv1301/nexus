@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('title','Pesquisa Avançada')

@push('css')
    <style>
        .base-button {
            transition: all 0.3s ease;
        }
        .base-button.selected {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .base-button:hover {
            background-color: #0056b3;
            color: white;
            border-color: #0056b3;
        }
        .btn-remove-criteria {
            border: none;
            background: none;
            color: #dc3545;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
        }
        .btn-remove-criteria:hover {
            color: #a71d2a;
            background-color: #f8d7da;
            border-radius: 0.25rem;
        }
        .table td {
            vertical-align: middle;
            padding: 0.5rem;
        }
        .table th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            padding: 0.75rem 0.5rem;
        }
        .form-control {
            font-size: 0.875rem;
        }
        .criteria-row:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

<x-page-header title="Pesquisa Avançada">
    <div>
        <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
           type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
    </div>
</x-page-header>

@section('content')
    @include('sweetalert::alert')
    
    <form action="{{ route('person.search.advanced.search') }}" method="post" id="form-advanced-search">
        @csrf
        
        <!-- Seleção de Bases de Dados -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex flex-wrap">
                    @can('nexus')
                    <button type="button" class="btn btn-outline-secondary mr-2 mb-2 base-button" data-value="nexus"
                            @if(in_array('nexus', old('bases', $request->bases ?? []))) data-selected="true" @endif>
                        NEXUS
                    </button>
                    @endcan
                    @can('nexus')
                    <button type="button" class="btn btn-outline-secondary mr-2 mb-2 base-button" data-value="faccionado"
                            @if(in_array('faccionado', old('bases', $request->bases ?? []))) data-selected="true" @endif>
                        FACCIONADO
                    </button>
                    @endcan
                </div>
                <!-- Hidden inputs for selected bases -->
                <div id="hidden-bases"></div>
            </div>
        </div>

        <!-- Critérios de Pesquisa - Tabela -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th width="10%">E/OU</th>
                                        <th width="12%">Agrupar</th>
                                        <th width="20%">Campo</th>
                                        <th width="15%">Operador</th>
                                        <th width="30%">Valor</th>
                                        <th width="8%">Ação</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="criteria-container">
                                    <!-- Os critérios serão adicionados dinamicamente aqui -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Botão para adicionar critério -->
                        <div class="text-right">
                            <button type="button" class="btn btn-success" id="add-criteria">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configurações de Ordenação e Paginação -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="order_field">Campo de Ordenação</label>
                    <select name="order_field" id="order_field" class="form-control">
                        <option value="name" @if(old('order_field', $request->order_field ?? '') == 'name') selected @endif>Nome</option>
                        <option value="cpf" @if(old('order_field', $request->order_field ?? '') == 'cpf') selected @endif>CPF</option>
                        <option value="birth_date" @if(old('order_field', $request->order_field ?? '') == 'birth_date') selected @endif>Data de Nascimento</option>
                        <option value="created_at" @if(old('order_field', $request->order_field ?? '') == 'created_at') selected @endif>Data de Cadastro</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="order_desc" name="order_direction" value="desc"
                               @if(old('order_direction', $request->order_direction ?? '') == 'desc') checked @endif>
                        <label class="form-check-label" for="order_desc">
                            Decrescente
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="pagination">Paginação</label>
                    <select name="pagination" id="pagination" class="form-control">
                        <option value="15" @if(old('pagination', $request->pagination ?? '') == '15') selected @endif>15</option>
                        <option value="25" @if(old('pagination', $request->pagination ?? '') == '25') selected @endif>25</option>
                        <option value="50" @if(old('pagination', $request->pagination ?? '') == '50') selected @endif>50</option>
                        <option value="100" @if(old('pagination', $request->pagination ?? '') == '100') selected @endif>100</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="limit">Limite</label>
                    <select name="limit" id="limit" class="form-control">
                        <option value="100" @if(old('limit', $request->limit ?? '') == '100') selected @endif>100</option>
                        <option value="250" @if(old('limit', $request->limit ?? '') == '250') selected @endif>250</option>
                        <option value="500" @if(old('limit', $request->limit ?? '') == '500') selected @endif>500</option>
                        <option value="1000" @if(old('limit', $request->limit ?? '') == '1000') selected @endif>1000</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Pesquisar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Área de Resultados -->
    <div class="row">
        <div class="col-12">
            @isset($bases)
                @include('search.person.list')
            @endisset
            
            @if(!isset($bases) || empty($bases))
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">* Selecione o Banco de Dados Para Pesquisar</h5>
                        <p class="text-muted">Configure os critérios de pesquisa e selecione as bases de dados desejadas.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let criteriaCount = 0;

            // Gerenciar seleção de bases
            const baseButtons = document.querySelectorAll('.base-button');
            const hiddenBasesContainer = document.getElementById('hidden-bases');
            
            baseButtons.forEach(button => {
                // Marcar botões já selecionados
                if (button.dataset.selected === 'true') {
                    button.classList.add('selected');
                    addHiddenInput(button.dataset.value);
                }
                
                button.addEventListener('click', function() {
                    if (this.classList.contains('selected')) {
                        this.classList.remove('selected');
                        removeHiddenInput(this.dataset.value);
                    } else {
                        this.classList.add('selected');
                        addHiddenInput(this.dataset.value);
                    }
                });
            });

            function addHiddenInput(value) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'bases[]';
                input.value = value;
                input.id = 'base-' + value;
                hiddenBasesContainer.appendChild(input);
            }

            function removeHiddenInput(value) {
                const input = document.getElementById('base-' + value);
                if (input) {
                    input.remove();
                }
            }

            // Template para um novo critério (linha da tabela)
            function createCriteriaRow(index = 0, existingData = {}) {
                const logicOptions = `
                    <td>
                        <select name="criteria[${index}][logic]" class="form-control form-control-sm">
                            <option value="AND" ${existingData.logic === 'AND' || !existingData.logic ? 'selected' : ''}>E</option>
                            <option value="OR" ${existingData.logic === 'OR' ? 'selected' : ''}>OU</option>
                        </select>
                    </td>
                `;

                return `
                    <tr class="criteria-row" data-index="${index}">
                        ${logicOptions}
                        <td>
                            <select name="criteria[${index}][group]" class="form-control form-control-sm">
                                <option value="none" ${existingData.group === 'none' || !existingData.group ? 'selected' : ''}>Não</option>
                                <option value="start" ${existingData.group === 'start' ? 'selected' : ''}>Início</option>
                                <option value="end" ${existingData.group === 'end' ? 'selected' : ''}>Fim</option>
                            </select>
                        </td>
                        <td>
                            <select name="criteria[${index}][field]" class="form-control form-control-sm" required>
                                <option value="">Selecione...</option>
                                
                                <!-- Dados Pessoais -->
                                <optgroup label="📄 Dados Pessoais">
                                    <option value="situacao" ${existingData.field === 'situacao' ? 'selected' : ''}>⚖️ Situação</option>
                                    <option value="name" ${existingData.field === 'name' ? 'selected' : ''}>👤 Nome ou Alcunha</option>
                                    <option value="cpf" ${existingData.field === 'cpf' ? 'selected' : ''}>🆔 CPF</option>
                                    <option value="cnpj" ${existingData.field === 'cnpj' ? 'selected' : ''}>🏢 CNPJ</option>
                                    <option value="rg" ${existingData.field === 'rg' ? 'selected' : ''}>🎫 RG</option>
                                    <option value="mother" ${existingData.field === 'mother' ? 'selected' : ''}>👩 Nome da Mãe</option>
                                    <option value="father" ${existingData.field === 'father' ? 'selected' : ''}>👨 Nome do Pai</option>
                                    <option value="birth_date" ${existingData.field === 'birth_date' ? 'selected' : ''}>📅 Data de Nascimento</option>
                                    <option value="birth_city" ${existingData.field === 'birth_city' ? 'selected' : ''}>🏙️ Município de Nascimento</option>
                                    <option value="tattoo" ${existingData.field === 'tattoo' ? 'selected' : ''}>👁️ Tatuagem</option>
                                    <option value="orcrim" ${existingData.field === 'orcrim' ? 'selected' : ''}>👥 Orcrim</option>
                                    <option value="area_atuacao" ${existingData.field === 'area_atuacao' ? 'selected' : ''}>🗺️ Área de Atuação</option>
                                </optgroup>

                                <!-- Endereços -->
                                <optgroup label="🏠 Endereços">
                                    <option value="city" ${existingData.field === 'city' ? 'selected' : ''}>🏢 Cidade</option>
                                </optgroup>

                                <!-- Contatos -->
                                <optgroup label="📞 Contatos">
                                    <option value="phone" ${existingData.field === 'phone' ? 'selected' : ''}>📱 Telefone</option>
                                </optgroup>

                                <!-- Redes Sociais -->
                                <optgroup label="📧 Social">
                                    <option value="email" ${existingData.field === 'email' ? 'selected' : ''}>✉️ E-mail</option>
                                </optgroup>

                                <!-- Infopen -->
                                <optgroup label="🏛️ Infopen">
                                    <option value="matricula" ${existingData.field === 'matricula' ? 'selected' : ''}>🎟️ Matrícula</option>
                                </optgroup>

                                <!-- Veículos -->
                                <optgroup label="🚗 Veículos">
                                    <option value="placa" ${existingData.field === 'placa' ? 'selected' : ''}>🚙 Placa</option>
                                </optgroup>

                                <!-- Antecedentes -->
                                <optgroup label="📋 Antecedentes">
                                    <option value="bo" ${existingData.field === 'bo' ? 'selected' : ''}>📄 BO</option>
                                    <option value="natureza" ${existingData.field === 'natureza' ? 'selected' : ''}>⚖️ Natureza</option>
                                </optgroup>

                                <!-- Processos -->
                                <optgroup label="⚖️ Processos">
                                    <option value="processo" ${existingData.field === 'processo' ? 'selected' : ''}>🏛️ Processo</option>
                                </optgroup>
                            </select>
                        </td>
                        <td>
                            <select name="criteria[${index}][operator]" class="form-control form-control-sm" required>
                                <option value="contains" ${existingData.operator === 'contains' || !existingData.operator ? 'selected' : ''}>Contém</option>
                                <option value="equals" ${existingData.operator === 'equals' ? 'selected' : ''}>Igual</option>
                                <option value="starts_with" ${existingData.operator === 'starts_with' ? 'selected' : ''}>Inicia com</option>
                                <option value="ends_with" ${existingData.operator === 'ends_with' ? 'selected' : ''}>Termina com</option>
                                <option value="not_contains" ${existingData.operator === 'not_contains' ? 'selected' : ''}>Não contém</option>
                                <option value="not_equals" ${existingData.operator === 'not_equals' ? 'selected' : ''}>Diferente</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="criteria[${index}][value]" class="form-control form-control-sm" 
                                   value="${existingData.value || ''}" required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn-remove-criteria" onclick="removeCriteria(${index})" title="Remover critério">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <td></td>
                    </tr>
                `;
            }

            // Adicionar novo critério
            document.getElementById('add-criteria').addEventListener('click', function() {
                const container = document.getElementById('criteria-container');
                const currentIndex = criteriaCount++;
                container.insertAdjacentHTML('beforeend', createCriteriaRow(currentIndex));
            });

            // Remover critério
            window.removeCriteria = function(index) {
                const criteriaRow = document.querySelector(`[data-index="${index}"]`);
                if (criteriaRow) {
                    criteriaRow.remove();
                }
            }

            // Carregar critérios existentes (se houver)
            @if(old('criteria') || (isset($request->criteria) && $request->criteria))
                const existingCriteria = @json(old('criteria', $request->criteria ?? []));
                if (existingCriteria && existingCriteria.length > 0) {
                    const container = document.getElementById('criteria-container');
                    existingCriteria.forEach((criteria, index) => {
                        container.insertAdjacentHTML('beforeend', createCriteriaRow(index, criteria));
                        criteriaCount = index + 1;
                    });
                }
            @else
                // Adicionar primeiro critério por padrão
                document.getElementById('add-criteria').click();
            @endif

            // Validação do formulário
            document.getElementById('form-advanced-search').addEventListener('submit', function(e) {
                console.log('Form submit triggered');
                
                const criteria = document.querySelectorAll('.criteria-row');
                const selectedBases = document.querySelectorAll('.base-button.selected');
                
                console.log('Criteria count:', criteria.length);
                console.log('Selected bases count:', selectedBases.length);
                
                if (criteria.length === 0) {
                    e.preventDefault();
                    alert('Por favor, adicione pelo menos um critério de pesquisa.');
                    return false;
                }

                if (selectedBases.length === 0) {
                    e.preventDefault();
                    alert('Por favor, selecione pelo menos uma base de dados.');
                    return false;
                }

                // Validar se todos os critérios estão preenchidos
                let hasEmptyFields = false;
                criteria.forEach((row, index) => {
                    const field = row.querySelector('select[name*="[field]"]').value;
                    const operator = row.querySelector('select[name*="[operator]"]').value;
                    const value = row.querySelector('input[name*="[value]"]').value;
                    
                    console.log(`Criterion ${index}:`, {field, operator, value});
                    
                    if (!field || !operator || !value.trim()) {
                        hasEmptyFields = true;
                    }
                });

                if (hasEmptyFields) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos dos critérios de pesquisa.');
                    return false;
                }

                // Log final data before submit
                const formData = new FormData(this);
                console.log('Form data being submitted:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
            });
        });
    </script>
@endpush
