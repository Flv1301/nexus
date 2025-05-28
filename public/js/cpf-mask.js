/**
 * Script para aplicar máscara específica de CPF
 * - Aceita apenas números
 * - Limita a 11 dígitos
 * - Remove caracteres não numéricos
 */

document.addEventListener('DOMContentLoaded', function() {
    
    /**
     * Função para aplicar máscara numérica de CPF
     */
    function applyCPFMask(element) {
        // Remove eventos existentes para evitar conflitos
        element.removeEventListener('input', handleCPFInput);
        element.removeEventListener('keypress', handleCPFKeypress);
        element.removeEventListener('paste', handleCPFPaste);
        
        // Adiciona novos event listeners
        element.addEventListener('input', handleCPFInput);
        element.addEventListener('keypress', handleCPFKeypress);
        element.addEventListener('paste', handleCPFPaste);
        
        // Configura atributos
        element.setAttribute('maxlength', '11');
        element.setAttribute('pattern', '[0-9]{11}');
        element.setAttribute('inputmode', 'numeric');
        
        // Limpa valor existente se houver caracteres inválidos
        if (element.value) {
            const cleaned = element.value.replace(/\D/g, '');
            element.value = cleaned.substring(0, 11);
        }
        
        console.log(`Máscara CPF numérica aplicada ao campo: ${element.name || element.id}`);
    }
    
    /**
     * Manipula evento de input
     */
    function handleCPFInput(event) {
        const element = event.target;
        let value = element.value;
        
        // Remove todos os caracteres não numéricos
        const numericValue = value.replace(/\D/g, '');
        
        // Limita a 11 dígitos
        const limitedValue = numericValue.substring(0, 11);
        
        // Atualiza o valor se foi modificado
        if (element.value !== limitedValue) {
            element.value = limitedValue;
        }
    }
    
    /**
     * Manipula evento de keypress (previne caracteres não numéricos)
     */
    function handleCPFKeypress(event) {
        // Permite: backspace, delete, tab, escape, enter
        if ([8, 9, 27, 13, 46].indexOf(event.keyCode) !== -1 ||
            // Permite: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (event.keyCode === 65 && event.ctrlKey === true) ||
            (event.keyCode === 67 && event.ctrlKey === true) ||
            (event.keyCode === 86 && event.ctrlKey === true) ||
            (event.keyCode === 88 && event.ctrlKey === true)) {
            return;
        }
        
        // Garante que é um número
        if ((event.shiftKey || (event.keyCode < 48 || event.keyCode > 57)) && (event.keyCode < 96 || event.keyCode > 105)) {
            event.preventDefault();
        }
        
        // Verifica se já atingiu o limite de 11 dígitos
        if (event.target.value.length >= 11) {
            event.preventDefault();
        }
    }
    
    /**
     * Manipula evento de paste
     */
    function handleCPFPaste(event) {
        setTimeout(() => {
            const element = event.target;
            let value = element.value;
            
            // Remove todos os caracteres não numéricos
            const numericValue = value.replace(/\D/g, '');
            
            // Limita a 11 dígitos
            const limitedValue = numericValue.substring(0, 11);
            
            element.value = limitedValue;
        }, 10);
    }
    
    /**
     * Função para encontrar e aplicar máscara a todos os campos CPF
     */
    function applyToAllCPFFields() {
                 // Lista de seletores para campos CPF
         const cpfSelectors = [
             'input[name="cpf"]',
             'input[name="spouse_cpf"]',
             'input[name="vinculo_cpf"]',
             'input[id="cpf"]',
             'input[id="spouse_cpf"]',
             'input[id="vinculo_cpf"]',
             'input.mask-cpf-number',
             'input.mask-cpf',
             'input[class*="cpf"]'
         ];
        
        let processedCount = 0;
        
        cpfSelectors.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                // Evita processar o mesmo elemento múltiplas vezes
                if (!element.hasAttribute('data-cpf-mask-applied')) {
                    applyCPFMask(element);
                    element.setAttribute('data-cpf-mask-applied', 'true');
                    processedCount++;
                }
            });
        });
        
        console.log(`Máscara CPF aplicada a ${processedCount} campos`);
        return processedCount;
    }
    
    /**
     * Observer para detectar novos campos CPF adicionados dinamicamente
     */
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    // Verifica se o próprio node é um campo CPF
                    if (node.tagName === 'INPUT' && isCPFField(node)) {
                        applyCPFMask(node);
                        node.setAttribute('data-cpf-mask-applied', 'true');
                    }
                    
                                         // Verifica campos CPF dentro do node adicionado
                     if (node.querySelectorAll) {
                         const cpfFields = node.querySelectorAll('input[name*="cpf"], input[id*="cpf"], input.mask-cpf-number, input.mask-cpf');
                         cpfFields.forEach(field => {
                             if (!field.hasAttribute('data-cpf-mask-applied')) {
                                 applyCPFMask(field);
                                 field.setAttribute('data-cpf-mask-applied', 'true');
                             }
                         });
                     }
                }
            });
        });
    });
    
    /**
     * Verifica se um campo é de CPF
     */
    function isCPFField(element) {
        const name = element.name || '';
        const id = element.id || '';
        const className = element.className || '';
        
                 return name.includes('cpf') || 
                id.includes('cpf') || 
                className.includes('cpf') ||
                className.includes('mask-cpf-number') ||
                className.includes('mask-cpf');
    }
    
    /**
     * Função global para aplicar máscara a um campo específico
     */
    window.applyCPFMaskToField = function(fieldNameOrElement) {
        let element;
        if (typeof fieldNameOrElement === 'string') {
            element = document.getElementById(fieldNameOrElement) || 
                     document.querySelector(`[name="${fieldNameOrElement}"]`);
        } else {
            element = fieldNameOrElement;
        }
        
        if (element && isCPFField(element)) {
            applyCPFMask(element);
            element.setAttribute('data-cpf-mask-applied', 'true');
            return true;
        }
        return false;
    };
    
    /**
     * Função global para reaplicar máscara a todos os campos CPF
     */
    window.reapplyAllCPFMasks = function() {
        console.log('Reaplicando máscaras de CPF...');
        return applyToAllCPFFields();
    };
    
    /**
     * Função para debug de campos CPF
     */
    window.debugCPFMasks = function() {
        console.log('=== DEBUG CPF MASKS ===');
        const allInputs = document.querySelectorAll('input');
        let cpfFieldsFound = 0;
        
        allInputs.forEach((input, index) => {
            if (isCPFField(input)) {
                cpfFieldsFound++;
                const hasMask = input.hasAttribute('data-cpf-mask-applied');
                console.log(`CPF Campo ${cpfFieldsFound}: Nome="${input.name}", ID="${input.id}", Classe="${input.className}", Máscara aplicada=${hasMask}`);
            }
        });
        
        console.log(`Total de campos CPF encontrados: ${cpfFieldsFound}`);
        console.log('=== FIM DEBUG CPF ===');
        return cpfFieldsFound;
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
                applyToAllCPFFields();
            }, 100);
        }
    });
    
    // Aplica máscaras aos campos existentes
    setTimeout(() => {
        applyToAllCPFFields();
    }, 100);
    
    console.log('Sistema de máscara CPF inicializado');
    console.log('Funções disponíveis: applyCPFMaskToField(), reapplyAllCPFMasks(), debugCPFMasks()');
}); 