// Teste específico para correção de placas
async function testPlacaNormalization() {
    console.log('🚗 Testando correção de normalização de placas...');
    
    const testCases = [
        { input: 'JUM0680', expected: 'JUM0680' },
        { input: 'JUM-0680', expected: 'JUM0680' },
        { input: 'jum0680', expected: 'JUM0680' },
        { input: 'ABC1234', expected: 'ABC1234' },
        { input: 'ABC-1234', expected: 'ABC1234' },
        { input: 'ABC1D23', expected: 'ABC1D23' },
        { input: 'ABC-1D23', expected: 'ABC1D23' }
    ];
    
    testCases.forEach(testCase => {
        // Simular normalização do backend
        const normalized = testCase.input.replace(/[^A-Z0-9]/gi, '').toUpperCase();
        
        if (normalized === testCase.expected) {
            console.log(`✅ ${testCase.input} → ${normalized}`);
        } else {
            console.error(`❌ ${testCase.input} → ${normalized} (esperado: ${testCase.expected})`);
        }
    });
    
    console.log('\n📋 Teste com dados reais:');
    try {
        // Teste com placa específica mencionada pelo usuário
        const placaInput = 'JUM-0680'; // Como vem da busca com máscara
        const placaBanco = 'JUM0680'; // Como está salvo no banco
        
        const inputNormalizado = placaInput.replace(/[^A-Z0-9]/gi, '').toUpperCase();
        const bancoNormalizado = placaBanco.replace(/[^A-Z0-9]/gi, '').toUpperCase();
        
        console.log(`Busca: "${placaInput}" → normalizado: "${inputNormalizado}"`);
        console.log(`Banco: "${placaBanco}" → normalizado: "${bancoNormalizado}"`);
        console.log(`Match: ${inputNormalizado === bancoNormalizado ? '✅ SIM' : '❌ NÃO'}`);
        
        // Teste de busca SQL simulada
        const sqlLike = `%${inputNormalizado}%`;
        console.log(`SQL LIKE pattern: "${sqlLike}"`);
        console.log(`Encontraria "${placaBanco}"? ${bancoNormalizado.includes(inputNormalizado) ? '✅ SIM' : '❌ NÃO'}`);
        
    } catch (error) {
        console.error('❌ Erro no teste:', error);
    }
}

// Teste específico para correção de telefones
async function testTelephoneNormalization() {
    console.log('📞 Testando correção de normalização de telefones...');
    
    const testCases = [
        { 
            input: '(91) 98069-6190', 
            expected: '91980696190',
            description: 'Busca com máscara completa' 
        },
        { 
            input: '91980696190', 
            expected: '91980696190',
            description: 'Busca só números' 
        },
        { 
            input: '980696190', 
            expected: '980696190',
            description: 'Busca apenas telefone sem DDD' 
        },
        { 
            input: '(91) 9806-96190', 
            expected: '91980696190',
            description: 'Busca com hífen em posição errada' 
        }
    ];
    
    testCases.forEach(testCase => {
        // Simular normalização do backend (remover tudo que não é dígito)
        const normalized = testCase.input.replace(/\D/g, '');
        
        if (normalized === testCase.expected) {
            console.log(`✅ ${testCase.description}: "${testCase.input}" → "${normalized}"`);
        } else {
            console.error(`❌ ${testCase.description}: "${testCase.input}" → "${normalized}" (esperado: "${testCase.expected}")`);
        }
    });
    
    console.log('\n📋 Teste com cenário real:');
    
    // Simulação do cenário real
    const bancoDDD = '91';           // DDD no banco
    const bancoTelefone = '980696190'; // Telefone no banco
    const buscaInput = '(91) 98069-6190'; // Como vem da busca com máscara
    
    const buscaNormalizada = buscaInput.replace(/\D/g, '');
    
    console.log(`Banco - DDD: "${bancoDDD}", Telefone: "${bancoTelefone}"`);
    console.log(`Busca: "${buscaInput}" → normalizado: "${buscaNormalizada}"`);
    
    // Teste das 3 estratégias de busca
    const estrategias = [
        {
            nome: '1. CONCAT(ddd, telephone)',
            match: (bancoDDD + bancoTelefone).includes(buscaNormalizada),
            query: `CONCAT('${bancoDDD}', '${bancoTelefone}') LIKE '%${buscaNormalizada}%'`
        },
        {
            nome: '2. Apenas telephone',
            match: bancoTelefone.includes(buscaNormalizada),
            query: `telephone LIKE '%${buscaNormalizada}%'`
        },
        {
            nome: '3. DDD + telephone separados',
            match: bancoDDD === buscaNormalizada.substr(0, 2) && bancoTelefone.includes(buscaNormalizada.substr(2)),
            query: `ddd='${buscaNormalizada.substr(0, 2)}' AND telephone LIKE '%${buscaNormalizada.substr(2)}%'`
        }
    ];
    
    estrategias.forEach(estrategia => {
        console.log(`${estrategia.match ? '✅' : '❌'} ${estrategia.nome}: ${estrategia.match ? 'ENCONTRARIA' : 'NÃO ENCONTRARIA'}`);
        console.log(`   SQL: ${estrategia.query}`);
    });
    
    const algumMatch = estrategias.some(e => e.match);
    console.log(`\n🎯 Resultado final: ${algumMatch ? '✅ BUSCA FUNCIONARÁ!' : '❌ BUSCA FALHARÁ!'}`);
} 