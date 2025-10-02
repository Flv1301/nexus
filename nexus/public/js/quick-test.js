// Script de Teste Rápido para Campos Dinâmicos
console.log('🚀 Iniciando teste rápido dos campos dinâmicos...');

// Aguardar o sistema estar pronto
setTimeout(() => {
    if (!window.dynamicFields) {
        console.error('❌ Sistema não carregado!');
        return;
    }

    console.log('✅ Sistema carregado. Iniciando testes...');

    // 1. Adicionar alguns campos
    console.log('📋 1. Adicionando campos...');
    window.dynamicFields.addField('cpf');
    window.dynamicFields.addField('name');
    window.dynamicFields.addField('rg');

    setTimeout(() => {
        // 2. Testar botão X individual
        console.log('📋 2. Testando botão X...');
        const firstButton = document.querySelector('.remove-field-btn');
        if (firstButton) {
            console.log('Clicando no primeiro botão X...');
            firstButton.click();
        } else {
            console.error('❌ Botão X não encontrado!');
        }

        setTimeout(() => {
            // 3. Verificar se foi removido
            const remainingButtons = document.querySelectorAll('.remove-field-btn');
            console.log(`📋 3. Botões restantes: ${remainingButtons.length} (esperado: 2)`);

            // 4. Testar removeAllFields
            console.log('📋 4. Testando removeAllFields...');
            
            // Simular confirmação
            const originalConfirm = window.confirm;
            window.confirm = () => {
                console.log('Confirmação simulada: SIM');
                return true;
            };

            window.dynamicFields.removeAllFields();
            window.confirm = originalConfirm;

            setTimeout(() => {
                // 5. Verificar se todos foram removidos
                const finalButtons = document.querySelectorAll('.remove-field-btn');
                const finalFields = document.querySelectorAll('.field-container');
                console.log(`📋 5. Campos finais: ${finalFields.length} (esperado: 0)`);
                console.log(`📋 5. Botões finais: ${finalButtons.length} (esperado: 0)`);

                if (finalFields.length === 0) {
                    console.log('🎉 SUCESSO! Todos os problemas foram corrigidos!');
                } else {
                    console.error('❌ FALHA! Ainda há problemas.');
                }

                console.log('📊 Estado final do sistema:');
                window.dynamicFields.debug();
            }, 1000);
        }, 1000);
    }, 1000);
}, 2000);

console.log('⏳ Aguarde 6 segundos para ver os resultados...'); 