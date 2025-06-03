@extends('adminlte::page')
@section('plugins.TempusDominusBs4', true)
@section('title','Editar Endereço')

<x-page-header title="Editar Endereço">
    <div>
        <a href="{{ route('person.show', $person->id) }}" class="btn btn-info">
            <i class="fas fa-sm fa-backward p-1"></i>Voltar para Pessoa
        </a>
    </div>
</x-page-header>

@section('content')
    @include('sweetalert::alert')
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt"></i> 
                        Editar Endereço de {{ $person->name }}
                    </h3>
                </div>
                
                <form action="{{ route('address.update', $address->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        @include('address.form')
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('person.show', $person->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Salvar Alterações
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Função para carregar cidades por UF (se não estiver disponível globalmente)
        function loadCitiesByUF(ufFieldId, cityFieldId) {
            const ufField = document.getElementById(ufFieldId);
            const cityField = document.getElementById(cityFieldId);
            
            if (!ufField.value) {
                cityField.innerHTML = '<option value="">Selecione primeiro a UF</option>';
                return;
            }
            
            // Buscar cidades via AJAX
            fetch(`{{ url('/api/cities-by-uf') }}?uf=${ufField.value}`)
                .then(response => response.json())
                .then(data => {
                    cityField.innerHTML = '<option value="">Selecione uma cidade</option>';
                    
                    if (data.cities) {
                        data.cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
                            option.textContent = city.name;
                            
                            // Manter cidade selecionada se existir
                            if (city.name === '{{ old("city") ?? $address->city ?? "" }}') {
                                option.selected = true;
                            }
                            
                            cityField.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar cidades:', error);
                    cityField.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                });
        }
        
        // Carregar cidades ao inicializar a página se UF estiver selecionada
        document.addEventListener('DOMContentLoaded', function() {
            const ufField = document.getElementById('uf');
            if (ufField && ufField.value) {
                loadCitiesByUF('uf', 'city');
            }
        });
    </script>
@endpush
