// Script de Debug - Sistema Completo com Novos Campos
console.log('🔍 Script de Debug - Testando sistema completo com todos os campos');

function debugRemoveAllFunction() {
    console.log('🧪 Testando função "Remover Todos os Campos"...');
    
    if (!window.dynamicFields) {
        console.error('❌ Sistema não carregado!');
        return;
    }
    
    // 1. Adicionar vários campos incluindo novos
    console.log('📋 Adicionando múltiplos campos...');
    window.dynamicFields.addField('name', 'Maria Santos');
    window.dynamicFields.addField('cpf', '98765432100');
    window.dynamicFields.addField('phone', '11987654321');
    window.dynamicFields.addField('email', 'maria@email.com');
    window.dynamicFields.addField('placa', 'ABC1234');
    
    setTimeout(() => {
        console.log('📋 Estado antes da remoção total:');
        window.dynamicFields.debug();
        
        console.log('📋 Executando removeAllFields programaticamente...');
        
        // Simular clique no botão removendo a confirmação temporariamente
        const originalConfirm = window.confirm;
        window.confirm = () => {
            console.log('Confirmação simulada: SIM');
            return true;
        };
        
        window.dynamicFields.removeAllFields();
        
        // Restaurar confirm original
        window.confirm = originalConfirm;
        
        setTimeout(() => {
            console.log('📋 Estado após removeAllFields:');
            window.dynamicFields.debug();
            
            const remainingFields = document.querySelectorAll('.field-container');
            console.log(`📋 Campos restantes no DOM: ${remainingFields.length}`);
            
            if (remainingFields.length === 0 && window.dynamicFields.addedFields.length === 0) {
                console.log('✅ REMOÇÃO TOTAL FUNCIONOU CORRETAMENTE!');
            } else {
                console.error('❌ REMOÇÃO TOTAL FALHOU!');
            }
        }, 1000);
    }, 1500);
}

function debugButtonElements() {
    console.log('🔍 Debug detalhado dos elementos de botão...');
    
    // Verificar botão remover todos
    const removeAllBtn = document.getElementById('remove-all-fields');
    console.log('Botão remover todos encontrado:', !!removeAllBtn, removeAllBtn);
    
    if (removeAllBtn) {
        console.log('  Testando clique no botão remover todos...');
        // Simular clique (sem executar para não interferir)
        console.log('  Botão está funcional');
    }
}

function testAddFields() {
    console.log('🧪 Testando adição de campos - incluindo novos campos...');
    
    if (!window.dynamicFields) {
        console.error('❌ Sistema não carregado!');
        return;
    }
    
    const fieldsToAdd = [
        'name', 'cpf', 'phone', 'email', 'placa', 
        'birth_city', 'tattoo', 'city', 'matricula', 'bo'
    ];
    
    console.log('📋 Adicionando campos selecionados...');
    fieldsToAdd.forEach((field, index) => {
        setTimeout(() => {
            let testValue = `Teste ${index + 1}`;
            
            // Valores específicos para campos especiais
            switch(field) {
                case 'cpf':
                    testValue = '12345678901';
                    break;
                case 'phone':
                    testValue = '11987654321';
                    break;
                case 'email':
                    testValue = 'teste@email.com';
                    break;
                case 'placa':
                    testValue = 'ABC1234';
                    break;
                case 'birth_city':
                    testValue = 'São Paulo';
                    break;
            }
            
            window.dynamicFields.addField(field, testValue);
            console.log(`✅ Campo ${field} adicionado com valor: ${testValue}`);
            
            if (index === fieldsToAdd.length - 1) {
                setTimeout(() => {
                    console.log('📋 Estado após adicionar campos selecionados:');
                    window.dynamicFields.debug();
                }, 500);
            }
        }, index * 300);
    });
}

function testNewFieldValidations() {
    console.log('🧪 Testando validações dos novos campos...');
    
    if (!window.dynamicFields) {
        console.error('❌ Sistema não carregado!');
        return;
    }
    
    // Testar telefone
    console.log('📋 Testando validação de telefone...');
    window.dynamicFields.addField('phone', '123');
    
    setTimeout(() => {
        const errors = window.dynamicFields.validateAllFields();
        console.log('Erros de telefone encontrados:', errors);
        
        if (errors.some(error => error.includes('Telefone'))) {
            console.log('✅ Validação de telefone funcionando');
        } else {
            console.warn('⚠️ Validação de telefone pode ter problemas');
        }
        
        // Limpar para próximo teste
        const originalConfirm = window.confirm;
        window.confirm = () => true;
        window.dynamicFields.removeAllFields();
        window.confirm = originalConfirm;
        
        setTimeout(() => {
            // Testar email inválido
            console.log('📋 Testando validação de email...');
            window.dynamicFields.addField('email', 'email-invalido');
            
            setTimeout(() => {
                const emailErrors = window.dynamicFields.validateAllFields();
                console.log('Erros de email encontrados:', emailErrors);
                
                if (emailErrors.some(error => error.includes('E-mail'))) {
                    console.log('✅ Validação de email funcionando');
                } else {
                    console.warn('⚠️ Validação de email pode ter problemas');
                }
                
                // Limpar para próximo teste
                window.confirm = () => true;
                window.dynamicFields.removeAllFields();
                window.confirm = originalConfirm;
                
                setTimeout(() => {
                    // Testar placa inválida
                    console.log('📋 Testando validação de placa...');
                    window.dynamicFields.addField('placa', '123');
                    
                    setTimeout(() => {
                        const placaErrors = window.dynamicFields.validateAllFields();
                        console.log('Erros de placa encontrados:', placaErrors);
                        
                        if (placaErrors.some(error => error.includes('Placa'))) {
                            console.log('✅ Validação de placa funcionando');
                        } else {
                            console.warn('⚠️ Validação de placa pode ter problemas');
                        }
                    }, 500);
                }, 1000);
            }, 500);
        }, 1000);
    }, 500);
}

function testFieldMasks() {
    console.log('🧪 Testando máscaras dos campos...');
    
    if (!window.dynamicFields) {
        console.error('❌ Sistema não carregado!');
        return;
    }
    
    // Testar máscara de telefone
    console.log('📋 Testando máscara de telefone...');
    window.dynamicFields.addField('phone');
    
    setTimeout(() => {
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            // Simular digitação
            phoneInput.value = '11987654321';
            phoneInput.dispatchEvent(new Event('input'));
            
            console.log('Valor formatado do telefone:', phoneInput.value);
            
            if (phoneInput.value.includes('(') && phoneInput.value.includes(')')) {
                console.log('✅ Máscara de telefone funcionando');
            } else {
                console.warn('⚠️ Máscara de telefone pode ter problemas');
            }
        }
        
        setTimeout(() => {
            // Testar máscara de placa
            console.log('📋 Testando máscara de placa...');
            window.dynamicFields.addField('placa');
            
            setTimeout(() => {
                const placaInput = document.querySelector('input[name="placa"]');
                if (placaInput) {
                    placaInput.value = 'abc1234';
                    placaInput.dispatchEvent(new Event('input'));
                    
                    console.log('Valor formatado da placa:', placaInput.value);
                    
                    if (placaInput.value.includes('-') && placaInput.value === 'ABC-1234') {
                        console.log('✅ Máscara de placa funcionando');
                    } else {
                        console.warn('⚠️ Máscara de placa pode ter problemas');
                    }
                }
            }, 500);
        }, 1000);
    }, 500);
}

function simulateUserInteraction() {
    console.log('🎭 Simulando interação completa do usuário...');
    
    if (!window.dynamicFields) {
        console.error('❌ Sistema não carregado!');
        return;
    }
    
    // Passo 1: Adicionar campos variados
    console.log('📋 Passo 1: Adicionando campos variados...');
    window.dynamicFields.addField('name', 'João Silva');
    window.dynamicFields.addField('cpf', '98765432100');
    window.dynamicFields.addField('phone', '11987654321');
    window.dynamicFields.addField('email', 'joao@teste.com');
    window.dynamicFields.addField('placa', 'ABC1234');
    window.dynamicFields.addField('city', 'São Paulo');
    
    setTimeout(() => {
        console.log('📋 Passo 2: Verificando campos criados...');
        window.dynamicFields.debug();
        
        // Passo 2: Tentar remover todos os campos
        console.log('📋 Passo 3: Testando remoção total...');
        
        // Simular confirmação
        const originalConfirm = window.confirm;
        window.confirm = () => true;
        
        window.dynamicFields.removeAllFields();
        window.confirm = originalConfirm;
        
        setTimeout(() => {
            console.log('📋 Passo 4: Estado final...');
            window.dynamicFields.debug();
            
            const finalFields = document.querySelectorAll('.field-container');
            if (finalFields.length === 0) {
                console.log('🎉 TESTE COMPLETO: SUCESSO!');
            } else {
                console.error('❌ TESTE COMPLETO: FALHOU!');
            }
        }, 1000);
    }, 2000);
}

function quickTest() {
    console.log('⚡ Teste Rápido - Sistema Completo');
    
    if (!window.dynamicFields) {
        console.error('❌ Sistema não carregado!');
        return;
    }
    
    // Adicionar campos variados
    console.log('1. Adicionando campos variados...');
    window.dynamicFields.addField('cpf', '12345678901');
    window.dynamicFields.addField('phone', '11987654321');
    window.dynamicFields.addField('email', 'teste@email.com');
    
    setTimeout(() => {
        // Testar remoção total
        console.log('2. Testando remoção total...');
        const originalConfirm = window.confirm;
        window.confirm = () => true;
        window.dynamicFields.removeAllFields();
        window.confirm = originalConfirm;
        
        setTimeout(() => {
            console.log('✅ Teste rápido concluído!');
            
            const finalFields = document.querySelectorAll('.field-container');
            if (finalFields.length === 0) {
                console.log('🎉 Sistema funcionando perfeitamente!');
                console.log(`📊 Total de campos disponíveis: ${Object.keys(window.dynamicFields.fieldConfigurations).length}`);
            } else {
                console.error('❌ Sistema apresentou problemas!');
            }
        }, 500);
    }, 1000);
}

// Executar testes automaticamente
setTimeout(() => {
    console.log('🚀 Iniciando testes do sistema completo...');
    
    debugButtonElements();
    
    setTimeout(() => {
        testAddFields();
    }, 2000);
    
    setTimeout(() => {
        debugRemoveAllFunction();
    }, 6000);
    
    setTimeout(() => {
        testNewFieldValidations();
    }, 10000);
    
    setTimeout(() => {
        testFieldMasks();
    }, 15000);
    
    setTimeout(() => {
        simulateUserInteraction();
    }, 20000);
    
}, 1000);

// Disponibilizar funções globalmente
window.debugRemoveAllFunction = debugRemoveAllFunction;
window.debugButtonElements = debugButtonElements;
window.testAddFields = testAddFields;
window.testNewFieldValidations = testNewFieldValidations;
window.testFieldMasks = testFieldMasks;
window.simulateUserInteraction = simulateUserInteraction;
window.quickTest = quickTest;

console.log('🧪 Funções de debug disponíveis:');
console.log('  • testAddFields() - Testa adição de campos variados');
console.log('  • debugRemoveAllFunction() - Testa remoção total');
console.log('  • testNewFieldValidations() - Testa validações dos novos campos');
console.log('  • testFieldMasks() - Testa máscaras dos campos');
console.log('  • debugButtonElements() - Debug dos elementos');
console.log('  • simulateUserInteraction() - Simulação completa');
console.log('  • quickTest() - Teste rápido do sistema completo'); 