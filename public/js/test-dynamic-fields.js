// Teste do Sistema de Campos Dinâmicos
// Execute no console do navegador para testar

function testDynamicFields() {
    console.log('🧪 Iniciando testes do sistema de campos dinâmicos...');
    
    if (!window.dynamicFields) {
        console.error('❌ DynamicSearchFields não foi inicializado!');
        return;
    }
    
    const df = window.dynamicFields;
    
    // Teste 1: Verificar inicialização (deve começar vazio)
    console.log('📊 1. Estado inicial (deve estar vazio):');
    df.debug();
    
    // Teste 2: Adicionar campo Nome
    console.log('📊 2. Adicionando campo Nome...');
    df.addField('name');
    
    // Teste 3: Adicionar campo CPF
    console.log('📊 3. Adicionando campo CPF...');
    df.addField('cpf');
    
    // Teste 4: Adicionar campo Data
    console.log('📊 4. Adicionando campo Data de Nascimento...');
    df.addField('birth_date');
    
    // Teste 5: Testar preenchimento de dados
    console.log('📊 5. Preenchendo dados de teste...');
    setTimeout(() => {
        const nameInput = document.querySelector('input[name="name"]');
        const cpfInput = document.querySelector('input[name="cpf"]');
        const dateInput = document.querySelector('input[name="birth_date"]');
        
        if (nameInput) nameInput.value = 'João Silva';
        if (cpfInput) cpfInput.value = '12345678901';
        if (dateInput) dateInput.value = '15/03/1990';
        
        console.log('📊 6. Dados preenchidos. Estado atual:');
        df.debug();
        
        // Teste 6: Testar limpeza
        console.log('📊 7. Testando limpeza de campos...');
        setTimeout(() => {
            df.clearAllFields();
            
            // Teste 7: Testar remoção de campo específico
            console.log('📊 8. Testando remoção de campo CPF...');
            setTimeout(() => {
                df.removeField('cpf');
                
                console.log('📊 9. Após remoção do CPF:');
                df.debug();
                
                // Teste 8: Testar remoção de todos os campos
                console.log('📊 10. Testando remoção de todos os campos...');
                setTimeout(() => {
                    df.removeAllFields();
                    
                    console.log('📊 11. Estado final (deve estar vazio):');
                    df.debug();
                    
                    console.log('✅ Testes concluídos!');
                }, 1000);
            }, 1000);
        }, 1000);
    }, 1000);
}

function testRemoveButtons() {
    console.log('🧪 Testando botões X de remoção individual...');
    
    if (!window.dynamicFields) {
        console.error('❌ DynamicSearchFields não encontrado');
        return;
    }

    // Adicionar alguns campos para teste
    console.log('📋 Adicionando campos para teste...');
    window.dynamicFields.addField('name');
    window.dynamicFields.addField('cpf');
    window.dynamicFields.addField('rg');

    setTimeout(() => {
        // Procurar botões de remoção
        const removeButtons = document.querySelectorAll('.remove-field-btn');
        console.log(`📋 Encontrados ${removeButtons.length} botões de remoção`);

        removeButtons.forEach((button, index) => {
            const fieldContainer = button.closest('.field-container');
            const fieldType = fieldContainer ? fieldContainer.getAttribute('data-field') : 'desconhecido';
            console.log(`📋 Botão ${index + 1}: Campo ${fieldType}`, button);
            
            // Verificar se o botão é clicável
            const isVisible = button.offsetParent !== null;
            const hasClick = button.onclick !== null;
            console.log(`   Visível: ${isVisible}, Tem onclick: ${hasClick}`);
        });

        // Testar clique no primeiro botão
        if (removeButtons.length > 0) {
            console.log('📋 Testando clique no primeiro botão X...');
            setTimeout(() => {
                const firstButton = removeButtons[0];
                console.log('Clicando no botão:', firstButton);
                
                // Simular clique real
                firstButton.click();
                
                setTimeout(() => {
                    console.log('📋 Estado após clique no botão X:');
                    window.dynamicFields.debug();
                }, 1000);
            }, 1000);
        }
    }, 1000);
}

function testRemoveAllFunctionality() {
    console.log('🧪 Testando funcionalidade "Remover Todos os Campos"...');
    
    if (!window.dynamicFields) {
        console.error('❌ DynamicSearchFields não encontrado');
        return;
    }

    // Adicionar vários campos
    console.log('📋 Adicionando múltiplos campos para teste...');
    window.dynamicFields.addField('name');
    window.dynamicFields.addField('cpf');
    window.dynamicFields.addField('rg');
    window.dynamicFields.addField('mother');

    setTimeout(() => {
        console.log('📋 Estado antes da remoção total:');
        window.dynamicFields.debug();
        
        console.log('📋 Executando removeAllFields programaticamente...');
        
        // Simular clique no botão removendo a confirmação temporariamente
        const originalConfirm = window.confirm;
        window.confirm = () => true; // Auto-confirma
        
        window.dynamicFields.removeAllFields();
        
        // Restaurar confirm original
        window.confirm = originalConfirm;
        
        setTimeout(() => {
            console.log('📋 Estado após removeAllFields:');
            window.dynamicFields.debug();
            
            const remainingFields = document.querySelectorAll('.field-container');
            console.log(`📋 Campos restantes no DOM: ${remainingFields.length}`);
        }, 1000);
    }, 1500);
}

function testFieldButtons() {
    console.log('🧪 Testando botões da interface...');
    
    // Testar botão de limpeza
    const clearBtn = document.getElementById('inputs-reset');
    if (clearBtn) {
        console.log('✅ Botão "Limpar Campos" encontrado');
        console.log('Estado do botão:', clearBtn.style.pointerEvents === 'none' ? 'Desabilitado' : 'Habilitado');
        // clearBtn.click(); // Descomente para testar
    } else {
        console.error('❌ Botão "Limpar Campos" não encontrado');
    }
    
    // Testar botão de remoção
    const removeBtn = document.getElementById('remove-all-fields');
    if (removeBtn) {
        console.log('✅ Botão "Remover Todos os Campos" encontrado');
        console.log('Estado do botão:', removeBtn.style.pointerEvents === 'none' ? 'Desabilitado' : 'Habilitado');
        // removeBtn.click(); // Descomente para testar
    } else {
        console.error('❌ Botão "Remover Todos os Campos" não encontrado');
    }
    
    // Testar seletor de campo
    const selector = document.getElementById('field-selector');
    if (selector) {
        console.log('✅ Seletor de campo encontrado');
        console.log('Opções disponíveis:', Array.from(selector.options).map(o => o.value));
    } else {
        console.error('❌ Seletor de campo não encontrado');
    }
}

function checkRequiredElements() {
    console.log('🔍 Verificando elementos necessários...');
    
    const elements = {
        'selected-fields': document.getElementById('selected-fields'),
        'field-selector': document.getElementById('field-selector'),
        'inputs-reset': document.getElementById('inputs-reset'),
        'remove-all-fields': document.getElementById('remove-all-fields'),
        'form-search': document.getElementById('form-search')
    };
    
    Object.entries(elements).forEach(([name, element]) => {
        if (element) {
            console.log(`✅ ${name}: encontrado`);
        } else {
            console.error(`❌ ${name}: NÃO encontrado`);
        }
    });
    
    // Verificar CSS
    const cssLink = document.querySelector('link[href*="dynamic-search-fields.css"]');
    if (cssLink) {
        console.log('✅ CSS do sistema carregado');
    } else {
        console.warn('⚠️ CSS do sistema pode não estar carregado');
    }
    
    // Verificar JavaScript
    if (window.dynamicFields) {
        console.log('✅ JavaScript do sistema carregado');
    } else {
        console.error('❌ JavaScript do sistema NÃO carregado');
    }

    // Verificar estado inicial
    const selectedFields = document.getElementById('selected-fields');
    if (selectedFields) {
        const fieldCount = selectedFields.children.length;
        console.log(`📊 Campos iniciais: ${fieldCount} (esperado: 0)`);
        
        if (fieldCount === 0) {
            console.log('✅ Estado inicial correto (sem campos)');
        } else {
            console.warn('⚠️ Estado inicial inesperado (deveria começar sem campos)');
        }
    }
}

function testFormValidation() {
    console.log('🧪 Testando validação do formulário...');
    
    const form = document.getElementById('form-search');
    if (!form) {
        console.error('❌ Formulário não encontrado');
        return;
    }

    // Testar envio sem campos
    console.log('📋 Testando envio sem campos...');
    const submitEvent = new Event('submit', { cancelable: true });
    form.dispatchEvent(submitEvent);
    
    // Adicionar um campo e testar
    if (window.dynamicFields) {
        console.log('📋 Adicionando campo CPF para teste...');
        window.dynamicFields.addField('cpf');
        
        setTimeout(() => {
            console.log('📋 Testando envio com campo vazio...');
            form.dispatchEvent(submitEvent);
            
            // Preencher campo e testar
            const cpfInput = document.querySelector('input[name="cpf"]');
            if (cpfInput) {
                cpfInput.value = '12345678901';
                console.log('📋 Testando envio com campo preenchido...');
                form.dispatchEvent(submitEvent);
            }
        }, 500);
    }
}

function testEventDelegation() {
    console.log('🧪 Testando event delegation dos botões X...');
    
    if (!window.dynamicFields) {
        console.error('❌ DynamicSearchFields não encontrado');
        return;
    }

    // Adicionar campos
    window.dynamicFields.addField('cpf');
    
    setTimeout(() => {
        const button = document.querySelector('.remove-field-btn');
        if (button) {
            console.log('📋 Botão encontrado:', button);
            
            // Testar diferentes tipos de clique
            console.log('📋 Testando clique direto no botão...');
            button.dispatchEvent(new MouseEvent('click', { bubbles: true }));
            
            setTimeout(() => {
                // Adicionar outro campo e testar clique no ícone
                window.dynamicFields.addField('rg');
                
                setTimeout(() => {
                    const icon = document.querySelector('.remove-field-btn i');
                    if (icon) {
                        console.log('📋 Testando clique no ícone dentro do botão...');
                        icon.dispatchEvent(new MouseEvent('click', { bubbles: true }));
                    }
                }, 500);
            }, 1000);
        }
    }, 500);
}

// Função principal de teste
function runAllTests() {
    console.clear();
    console.log('🚀 Executando todos os testes do sistema de campos dinâmicos');
    console.log('=' .repeat(60));
    
    checkRequiredElements();
    console.log('');
    
    testFieldButtons();
    console.log('');
    
    testFormValidation();
    console.log('');

    testRemoveButtons();
    console.log('');

    setTimeout(() => {
        testEventDelegation();
    }, 3000);

    setTimeout(() => {
        testRemoveAllFunctionality();
    }, 6000);
    
    if (window.dynamicFields) {
        setTimeout(() => {
            testDynamicFields();
        }, 9000);
    } else {
        console.error('❌ Não é possível executar testes funcionais - DynamicSearchFields não encontrado');
    }
}

// Funções para testes específicos
function testAddAllFields() {
    console.log('🧪 Adicionando todos os campos disponíveis...');
    
    if (!window.dynamicFields) {
        console.error('❌ DynamicSearchFields não encontrado');
        return;
    }

    const fields = ['name', 'cpf', 'rg', 'mother', 'father', 'birth_date'];
    fields.forEach((field, index) => {
        setTimeout(() => {
            window.dynamicFields.addField(field);
            console.log(`✅ Campo ${field} adicionado`);
        }, index * 300);
    });
}

function testRemoveAllFields() {
    console.log('🧪 Removendo todos os campos...');
    
    if (!window.dynamicFields) {
        console.error('❌ DynamicSearchFields não encontrado');
        return;
    }

    window.dynamicFields.removeAllFields();
}

function testClickRemoveButton() {
    console.log('🧪 Teste específico do botão X...');
    
    // Adicionar um campo primeiro
    if (window.dynamicFields) {
        window.dynamicFields.addField('cpf');
        
        setTimeout(() => {
            const removeButton = document.querySelector('.remove-field-btn');
            if (removeButton) {
                console.log('📋 Botão X encontrado, clicando...', removeButton);
                
                // Teste múltiplos tipos de evento
                console.log('📋 Disparando evento mousedown...');
                removeButton.dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
                
                setTimeout(() => {
                    console.log('📋 Disparando evento click...');
                    removeButton.dispatchEvent(new MouseEvent('click', { bubbles: true }));
                }, 100);
            } else {
                console.error('❌ Botão X não encontrado');
            }
        }, 500);
    }
}

// Exportar funções para uso global
window.testDynamicFields = testDynamicFields;
window.testFieldButtons = testFieldButtons;
window.checkRequiredElements = checkRequiredElements;
window.testFormValidation = testFormValidation;
window.testAddAllFields = testAddAllFields;
window.testRemoveAllFields = testRemoveAllFields;
window.testRemoveButtons = testRemoveButtons;
window.testClickRemoveButton = testClickRemoveButton;
window.testEventDelegation = testEventDelegation;
window.testRemoveAllFunctionality = testRemoveAllFunctionality;
window.runAllTests = runAllTests;

console.log('🧪 Arquivo de teste carregado.');
console.log('📋 Funções disponíveis:');
console.log('  • runAllTests() - Executa todos os testes');
console.log('  • testAddAllFields() - Adiciona todos os campos');
console.log('  • testRemoveAllFields() - Remove todos os campos');
console.log('  • testRemoveButtons() - Testa botões X individuais');
console.log('  • testClickRemoveButton() - Teste específico do botão X');
console.log('  • testEventDelegation() - Testa event delegation');
console.log('  • testRemoveAllFunctionality() - Testa remoção em massa');
console.log('  • checkRequiredElements() - Verifica elementos necessários');
console.log('  • testFormValidation() - Testa validação do formulário'); 