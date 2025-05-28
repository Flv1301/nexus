/**
 * Script para aplicar máscara de caixa alta automaticamente
 * em campos de texto do sistema de cadastro de pessoas
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Lista de campos que devem ser convertidos para maiúscula
    const fieldsToUppercase = [
        // Campos de dados pessoais
        'name', 'nickname', 'birth_city', 'father', 'mother', 'spouse_name', 'tatto',
        'orcrim', 'orcrim_office', 'orcrim_occupation_area', 'occupation',
        
        // Campos de endereço
        'address', 'district', 'city', 'complement', 'state', 'reference_point',
        
        // Campos de empresas
        'new_company_name', 'new_fantasy_name', 'new_address', 'new_district', 'new_city', 'new_cnae', 'new_accountant',
        'company_name', 'fantasy_name', 'address', 'district', 'city', 'cnae', 'accountant',
        
        // Campos de veículos
        'new_vehicle_brand', 'new_vehicle_model', 'new_vehicle_color', 'new_vehicle_plate', 'new_vehicle_jurisdiction',
        'brand', 'model', 'color', 'plate', 'jurisdiction', 'status',
        
        // Campos de vínculos orcrim
        'vinculo_name', 'vinculo_tipo_vinculo', 'vinculo_orcrim', 'vinculo_cargo', 'vinculo_area_atuacao',
        
        // Campos de antecedentes/pcpa
        'pcpa_natureza',
        
        // Campos de TJ
        'tj_natureza',
        
        // Campos de armas
        'arma_marca', 'arma_modelo', 'arma_calibre',
        
        // Campos de RAIS
        'rais_empresa_orgao', 'rais_tipo_vinculo', 'rais_situacao',
        
        // Campos bancários
        'bancario_banco',
        
        // Campos de documentos
        'doc_nome_doc',
        
        // Campos de contatos e sociais
        'description', 'social_network', 'url'
    ];
    
    // Lista de tipos de campos que devem ser convertidos
    const fieldTypesToUppercase = [
        'text', 'search'
    ];
    
    // Lista de campos que devem ser EXCLUÍDOS (números, emails, etc.)
    const excludeFields = [
        'cpf', 'rg', 'spouse_cpf', 'voter_registration', 'conselho_de_classe',
        'cep', 'code', 'number', 'telephone', 'ddd', 'cnpj', 'phone', 'social_capital',
        'email', 'password', 'year', 'bo', 'processo', 'cac', 'conta', 'agencia',
        'password_confirmation', 'search', 'new_cep', 'new_cnpj', 'new_phone', 
        'new_social_capital', 'new_number', 'new_vehicle_year', 'vinculo_cpf',
        'pcpa_bo', 'tj_processo', 'arma_cac', 'bancario_conta', 'bancario_agencia'
    ];
    
    // Lista de palavras-chave que indicam campos numéricos ou especiais
    const excludeKeywords = [
        'cpf', 'cnpj', 'rg', 'telefone', 'phone', 'email', 'password', 'cep', 
        'numero', 'number', 'data', 'date', 'ano', 'year', 'valor', 'price',
        'quantidade', 'quantity', 'codigo', 'code', 'id'
    ];
    
    /**
     * Função para converter texto para maiúscula
     */
    function convertToUppercase(event) {
        const element = event.target;
        const cursorPosition = element.selectionStart;
        const value = element.value;
        const uppercaseValue = value.toUpperCase();
        
        if (value !== uppercaseValue) {
            element.value = uppercaseValue;
            // Mantém a posição do cursor
            element.setSelectionRange(cursorPosition, cursorPosition);
        }
    }
    
    /**
     * Função para verificar se um campo deve ser convertido
     */
    function shouldConvertField(element) {
        const name = element.name || element.id || '';
        const type = element.type || '';
        const className = element.className || '';
        const placeholder = element.placeholder || '';
        const label = element.getAttribute('aria-label') || '';
        
        // Verifica se o campo está na lista de exclusão
        if (excludeFields.includes(name)) {
            return false;
        }
        
        // Verifica se o nome contém palavras-chave de exclusão
        for (const keyword of excludeKeywords) {
            if (name.toLowerCase().includes(keyword)) {
                return false;
            }
        }
        
        // Verifica se já tem style="text-transform:uppercase" ou atributo style inline
        if (element.style.textTransform === 'uppercase' || 
            element.getAttribute('style')?.includes('text-transform:uppercase')) {
            return true;
        }
        
        // Verifica se está na lista de campos específicos
        if (fieldsToUppercase.includes(name)) {
            return true;
        }
        
        // Verificação específica para campos problemáticos
        if (name === 'vinculo_cargo' || name === 'rais_empresa_orgao') {
            return true;
        }
        
        // Exclui campos com máscaras específicas (CPF, CNPJ, etc.)
        if (className.includes('mask-')) {
            return false;
        }
        
        // Verifica se o tipo de campo deve ser convertido
        if (fieldTypesToUppercase.includes(type) || type === '') {
            // Aplica regras adicionais para campos de texto genéricos
            
                         // Verifica palavras-chave que indicam campos de texto/nome
             const textKeywords = ['nome', 'name', 'descri', 'marca', 'model', 'cor', 'color', 'endereco', 'address', 'bairro', 'district', 'cidade', 'city', 'empresa', 'company', 'fantasia', 'fantasy', 'cargo', 'office', 'area', 'natureza', 'observ', 'orgao', 'vinculo', 'orcrim'];
            
            for (const keyword of textKeywords) {
                if (name.toLowerCase().includes(keyword) || 
                    placeholder.toLowerCase().includes(keyword) ||
                    label.toLowerCase().includes(keyword)) {
                    return true;
                }
            }
            
            // Se for um campo de texto simples sem indicadores especiais, aplica a máscara
            return !name.includes('email') && 
                   !name.includes('url') &&
                   !name.includes('password') &&
                   !name.includes('search') &&
                   !className.includes('summernote') && // Exclui editores de texto rico
                   element.tagName !== 'TEXTAREA' || name.includes('observ'); // Textareas apenas se for observação
        }
        
        return false;
    }
    
    /**
     * Função para aplicar máscara a um elemento
     */
    function applyUppercaseMask(element) {
        // Verifica se já foi aplicado
        if (element.hasAttribute('data-uppercase-applied')) {
            return;
        }
        
        if (shouldConvertField(element)) {
            // Adiciona estilo visual
            element.style.textTransform = 'uppercase';
            
            // Adiciona event listeners
            element.addEventListener('input', convertToUppercase);
            element.addEventListener('paste', function(event) {
                // Aguarda um pequeno delay para o paste ser processado
                setTimeout(() => convertToUppercase(event), 10);
            });
            
            // Converte valor existente se houver
            if (element.value) {
                element.value = element.value.toUpperCase();
            }
            
            // Marca como processado
            element.setAttribute('data-uppercase-applied', 'true');
            
            console.log(`Máscara de caixa alta aplicada ao campo: ${element.name || element.id}`);
        }
    }
    
    /**
     * Função para aplicar máscaras a todos os campos existentes
     */
    function applyMasksToExistingFields() {
        const inputs = document.querySelectorAll('input[type="text"], input[type="search"], input:not([type])');
        inputs.forEach(applyUppercaseMask);
        
        // Também aplica a textareas específicas (excluindo editores de texto ricos)
        const textareas = document.querySelectorAll('textarea:not([data-editor])');
        textareas.forEach(textarea => {
            if (shouldConvertField(textarea)) {
                applyUppercaseMask(textarea);
            }
        });
    }
    
    /**
     * Observer para detectar novos campos adicionados dinamicamente
     */
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    // Verifica se o próprio node é um input
                    if (node.tagName === 'INPUT' || node.tagName === 'TEXTAREA') {
                        applyUppercaseMask(node);
                    }
                    
                    // Verifica inputs dentro do node adicionado
                    const inputs = node.querySelectorAll ? 
                        node.querySelectorAll('input[type="text"], input[type="search"], input:not([type]), textarea:not([data-editor])') : 
                        [];
                    inputs.forEach(applyUppercaseMask);
                }
            });
        });
    });
    
    /**
     * Função global para aplicar máscara a um campo específico (uso manual)
     */
    window.applyUppercaseMaskToField = function(fieldNameOrElement) {
        let element;
        if (typeof fieldNameOrElement === 'string') {
            element = document.getElementById(fieldNameOrElement) || document.querySelector(`[name="${fieldNameOrElement}"]`);
        } else {
            element = fieldNameOrElement;
        }
        
        if (element) {
            applyUppercaseMask(element);
        }
    };
    
    /**
     * Função para reaplicar máscaras em todas as abas (útil para campos dinâmicos)
     */
    window.reapplyAllUppercaseMasks = function() {
        console.log('Reaplicando máscaras de caixa alta...');
        const inputs = document.querySelectorAll('input[type="text"], input[type="search"], input:not([type]), textarea:not([data-editor])');
        inputs.forEach(applyUppercaseMask);
        console.log(`Processados ${inputs.length} campos`);
    };
    
    // Inicia o observer
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Adiciona listener para cliques em abas
    document.addEventListener('click', function(event) {
        if (event.target.matches('[data-toggle="tab"]') || event.target.closest('[data-toggle="tab"]')) {
            // Aguarda um pouco para a aba carregar
            setTimeout(() => {
                applyMasksToExistingFields();
            }, 100);
        }
    });
    
    // Aplica máscaras aos campos existentes
    applyMasksToExistingFields();
    
    // Força aplicação em campos específicos que podem ter problemas
    setTimeout(() => {
        const specificFields = ['vinculo_cargo', 'rais_empresa_orgao'];
        specificFields.forEach(fieldName => {
            const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                applyUppercaseMask(field);
                console.log(`Máscara forçada aplicada ao campo: ${fieldName}`);
            }
        });
    }, 500);
    
    // Função para debug - mostra campos detectados
    window.debugUppercaseMask = function() {
        const inputs = document.querySelectorAll('input[type="text"], input[type="search"], input:not([type]), textarea:not([data-editor])');
        console.log('=== DEBUG UPPERCASE MASK ===');
        console.log(`Total de campos encontrados: ${inputs.length}`);
        
        inputs.forEach((input, index) => {
            const shouldConvert = shouldConvertField(input);
            console.log(`${index + 1}. Nome: "${input.name || input.id}", Tipo: "${input.type}", Será convertido: ${shouldConvert}`);
        });
        
        // Debug específico para campos problemáticos
        console.log('\n=== CAMPOS ESPECÍFICOS ===');
        const specificFields = ['vinculo_cargo', 'rais_empresa_orgao'];
        specificFields.forEach(fieldName => {
            const field = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                const shouldConvert = shouldConvertField(field);
                const hasListener = field.hasAttribute('data-uppercase-applied');
                console.log(`Campo ${fieldName}: Encontrado=${!!field}, Será convertido=${shouldConvert}, Máscara aplicada=${hasListener}`);
            } else {
                console.log(`Campo ${fieldName}: NÃO ENCONTRADO`);
            }
        });
        
        console.log('=== FIM DEBUG ===');
    };
    
    console.log('Sistema de máscara de caixa alta inicializado');
    console.log('Para debug, execute: debugUppercaseMask() no console');
}); 