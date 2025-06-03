/**
 * Script para seleção dinâmica de cidades baseada na UF
 * Usa a API do IBGE para buscar municípios por estado
 */

// Cache para evitar múltiplas requisições para o mesmo estado
const citiesCache = {};

/**
 * Carrega as cidades baseado na UF selecionada
 * @param {string} ufFieldId - ID do campo UF
 * @param {string} cityFieldId - ID do campo cidade
 */
async function loadCitiesByUF(ufFieldId, cityFieldId) {
    const ufField = document.getElementById(ufFieldId);
    const cityField = document.getElementById(cityFieldId);
    
    if (!ufField || !cityField) {
        console.error('Campos UF ou cidade não encontrados:', ufFieldId, cityFieldId);
        return;
    }
    
    const selectedUF = ufField.value;
    
    // Limpar campo de cidade
    cityField.innerHTML = '<option value="">Carregando cidades...</option>';
    cityField.disabled = true;
    
    // Atualizar campo state se existir
    updateStateField(ufFieldId);
    
    if (!selectedUF) {
        cityField.innerHTML = '<option value="">Selecione primeiro a UF</option>';
        return;
    }
    
    try {
        // Verificar se já temos os dados em cache
        if (citiesCache[selectedUF]) {
            populateCityField(cityField, citiesCache[selectedUF]);
            return;
        }
        
        // Fazer requisição para a API
        const response = await fetch(`/api/cities-by-uf?uf=${selectedUF}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.cities) {
            // Armazenar no cache
            citiesCache[selectedUF] = data.cities;
            
            // Popular o campo
            populateCityField(cityField, data.cities);
        } else {
            throw new Error(data.message || 'Erro ao carregar cidades');
        }
        
    } catch (error) {
        console.error('Erro ao carregar cidades:', error);
        cityField.innerHTML = '<option value="">Erro ao carregar cidades. Tente novamente.</option>';
        
        // Mostrar notificação para o usuário (se disponível)
        if (typeof toastr !== 'undefined') {
            toastr.error('Erro ao carregar cidades. Verifique sua conexão e tente novamente.');
        } else if (typeof toast !== 'undefined') {
            toast('Erro ao carregar cidades', 'error');
        }
    } finally {
        cityField.disabled = false;
    }
}

/**
 * Popula o campo de cidade com as opções
 * @param {HTMLElement} cityField - Campo de cidade
 * @param {Array} cities - Array de cidades
 */
function populateCityField(cityField, cities) {
    // Obter valor atual para manter selecionado se existir
    const currentValue = cityField.dataset.currentValue || '';
    
    // Limpar campo
    cityField.innerHTML = '<option value="">Selecione uma cidade</option>';
    
    // Adicionar cidades
    cities.forEach(city => {
        const option = document.createElement('option');
        option.value = city.nome;
        option.textContent = city.nome;
        
        // Manter seleção se for o valor atual
        if (city.nome === currentValue) {
            option.selected = true;
        }
        
        cityField.appendChild(option);
    });
}

/**
 * Converte UF para nome completo do estado
 * @param {string} uf - Sigla do estado
 * @returns {string} - Nome completo do estado
 */
function getStateFromUF(uf) {
    const states = {
        'AC': 'Acre',
        'AL': 'Alagoas',
        'AP': 'Amapá',
        'AM': 'Amazonas',
        'BA': 'Bahia',
        'CE': 'Ceará',
        'DF': 'Distrito Federal',
        'ES': 'Espírito Santo',
        'GO': 'Goiás',
        'MA': 'Maranhão',
        'MT': 'Mato Grosso',
        'MS': 'Mato Grosso do Sul',
        'MG': 'Minas Gerais',
        'PA': 'Pará',
        'PB': 'Paraíba',
        'PR': 'Paraná',
        'PE': 'Pernambuco',
        'PI': 'Piauí',
        'RJ': 'Rio de Janeiro',
        'RN': 'Rio Grande do Norte',
        'RS': 'Rio Grande do Sul',
        'RO': 'Rondônia',
        'RR': 'Roraima',
        'SC': 'Santa Catarina',
        'SP': 'São Paulo',
        'SE': 'Sergipe',
        'TO': 'Tocantins'
    };
    
    return states[uf] || '';
}

/**
 * Atualiza o campo state automaticamente baseado na UF
 * @param {string} ufFieldId - ID do campo UF
 */
function updateStateField(ufFieldId) {
    const ufField = document.getElementById(ufFieldId);
    const stateField = document.getElementById('state');
    
    if (ufField && stateField && ufField.value) {
        stateField.value = getStateFromUF(ufField.value);
    }
}

/**
 * Inicialização quando o DOM estiver carregado
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('DynamicCities: Script carregado');
    
    // Configurar campos que já têm valores para preservar a seleção
    const cityFields = ['birth_city', 'detainee_city', 'city', 'new_city'];
    
    cityFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && field.value) {
            field.dataset.currentValue = field.value;
        }
    });
    
    // Carregar cidades automaticamente se UF já estiver selecionada
    const ufMappings = [
        { uf: 'uf_birth_city', city: 'birth_city' },
        { uf: 'detainee_uf', city: 'detainee_city' },
        { uf: 'uf', city: 'city' },
        { uf: 'new_uf', city: 'new_city' }  // Mapeamento para empresas
    ];
    
    ufMappings.forEach(mapping => {
        const ufField = document.getElementById(mapping.uf);
        if (ufField && ufField.value) {
            setTimeout(() => {
                loadCitiesByUF(mapping.uf, mapping.city);
            }, 500);
        }
    });
});

/**
 * Busca cidades por nome (para autocomplete futuro)
 * @param {string} searchTerm - Termo de busca
 * @param {string} uf - UF para filtrar (opcional)
 * @returns {Promise<Array>} - Array de cidades encontradas
 */
async function searchCitiesByName(searchTerm, uf = '') {
    try {
        const response = await fetch(`/api/search-cities?search=${encodeURIComponent(searchTerm)}&uf=${uf}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.cities) {
            return data.cities;
        } else {
            throw new Error(data.message || 'Erro ao buscar cidades');
        }
        
    } catch (error) {
        console.error('Erro ao buscar cidades:', error);
        return [];
    }
}

// Expor funções globalmente
window.loadCitiesByUF = loadCitiesByUF;
window.searchCitiesByName = searchCitiesByName;
window.updateStateField = updateStateField; 