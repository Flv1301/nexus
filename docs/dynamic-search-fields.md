# Sistema de Campos Dinâmicos - Pesquisa de Pessoa

## Visão Geral
O Sistema de Campos Dinâmicos permite que usuários construam formulários de pesquisa personalizados adicionando apenas os campos necessários para sua consulta. **Todos os campos são opcionais**, permitindo máxima flexibilidade na busca.

## Características Principais

### ✅ Flexibilidade Total
- **Nenhum campo obrigatório**: O usuário pode começar sem nenhum campo e adicionar apenas os que precisa
- **Pesquisa específica**: Possibilidade de pesquisar apenas por CPF, apenas por data de nascimento, etc.
- **Campo Nome opcional**: Diferentemente de sistemas tradicionais, o nome não é obrigatório

### ✅ Campos Disponíveis
1. **Nome ou Alcunha** - Campo de texto livre
2. **CPF** - Validação automática para 11 dígitos
3. **RG** - Campo de texto livre
4. **Nome da Mãe** - Campo de texto livre  
5. **Nome do Pai** - Campo de texto livre
6. **Data de Nascimento** - Máscara DD/MM/AAAA com validação

### ✅ Funcionalidades
- **Adição dinâmica**: Seletor dropdown para adicionar campos
- **Remoção individual**: Botão X em cada campo
- **Remoção em massa**: Botão "Remover Todos os Campos"
- **Limpeza de valores**: Botão "Limpar Campos" (limpa apenas os valores)
- **Validação inteligente**: CPF e datas são validados antes do envio
- **Persistência**: Campos e valores mantidos após submit/erros de validação
- **Animações**: Transições suaves para melhor UX

## Como Usar

### 1. Estado Inicial
- O formulário inicia **vazio**, sem nenhum campo
- Uma mensagem amigável orienta o usuário a adicionar campos
- Os botões de limpeza ficam desabilitados até que campos sejam adicionados

### 2. Adicionando Campos
1. Use o seletor dropdown "Selecionar Campo"
2. Escolha o campo desejado da lista
3. O campo aparece instantaneamente com animação
4. O foco é automaticamente direcionado para o novo campo
5. O contador de campos disponíveis é atualizado

### 3. Preenchendo Dados
- **CPF**: Aceita apenas números (máximo 11 dígitos)
- **Data**: Máscara automática DD/MM/AAAA
- **Outros campos**: Texto livre sem restrições

### 4. Removendo Campos
- **Individual**: Clique no X do campo desejado
- **Todos**: Use "Remover Todos os Campos" (com confirmação)

### 5. Limpeza de Dados
- **"Limpar Campos"**: Remove apenas os valores preenchidos
- **"Remover Todos os Campos"**: Remove os campos do formulário

### 6. Pesquisando
- O sistema valida que pelo menos um campo foi preenchido
- Validações específicas (CPF, data) são aplicadas
- O formulário é enviado apenas se tudo estiver correto

## Exemplos de Uso

### Pesquisa apenas por CPF
1. Adicione o campo "CPF"
2. Digite o CPF desejado
3. Clique em "Pesquisar"

### Pesquisa por Nome e Data de Nascimento
1. Adicione o campo "Nome ou Alcunha"
2. Adicione o campo "Data de Nascimento"
3. Preencha os valores
4. Clique em "Pesquisar"

### Pesquisa completa
1. Adicione todos os campos necessários
2. Preencha os valores conhecidos
3. Deixe campos vazios se não souber as informações
4. Clique em "Pesquisar"

## Validações Implementadas

### Validação do CPF
- Deve conter exatamente 11 dígitos
- Apenas números são aceitos
- Validação em tempo real

### Validação de Data
- Formato obrigatório: DD/MM/AAAA
- Verificação de data válida
- Ano entre 1900 e ano atual
- Validação em tempo real

### Validação do Formulário
- Pelo menos um campo deve estar preenchido
- Todos os campos preenchidos devem ser válidos
- Mensagens de erro claras e específicas

## Integração com o Sistema

### Arquivos Envolvidos
- **JavaScript**: `public/js/dynamic-search-fields.js`
- **CSS**: `public/css/dynamic-search-fields.css`
- **View**: `resources/views/search/person/index.blade.php`
- **Controller**: Mantém compatibilidade com `PersonSearchController`

### Compatibilidade
- ✅ Integração com máscara de CPF existente (`cpf-mask.js`)
- ✅ Mantém funcionalidade dos checkboxes (Nexus/Faccionado/Cortex)
- ✅ Preserva rotas e métodos existentes
- ✅ Compatível com sistema de validação do Laravel

## Feedback Visual

### Estados dos Botões
- **Desabilitados**: Quando não há campos ou campos preenchidos
- **Habilitados**: Quando há campos adicionados ou valores preenchidos
- **Hover**: Efeitos visuais em todos os elementos interativos

### Notificações (Toasts)
- **Sucesso**: Campos adicionados, removidos, ou limpos
- **Aviso**: Validações e orientações
- **Erro**: Problemas de validação
- **Info**: Informações gerais

### Animações
- **Entrada de campos**: Slide down com fade in
- **Saída de campos**: Slide up com fade out
- **Hover**: Elevação sutil dos elementos
- **Focus**: Destaque visual dos campos ativos

## Acessibilidade

- **Navegação por teclado**: Totalmente suportada
- **Focus trap**: Indicadores visuais claros
- **Labels descritivos**: Todos os campos têm labels apropriados
- **Cores contrastantes**: Design acessível para daltonismo
- **Textos alternativos**: Ícones com títulos descritivos

## Responsividade

- **Mobile first**: Design otimizado para dispositivos móveis
- **Breakpoints**: Ajustes automáticos para diferentes tamanhos de tela
- **Touch friendly**: Botões e áreas de toque adequadas

## Melhorias Futuras

### Funcionalidades Planejadas
- [ ] Salvamento de templates de pesquisa
- [ ] Histórico de pesquisas
- [ ] Exportação de resultados
- [ ] Pesquisa por voz
- [ ] Autocompletar inteligente

### Otimizações Técnicas
- [ ] Lazy loading de campos
- [ ] Cache de validações
- [ ] Debounce em validações em tempo real
- [ ] Service Worker para uso offline

## Suporte e Manutenção

### Para Desenvolvedores
- Código totalmente documentado
- Arquitetura modular e extensível
- Testes automatizados disponíveis
- Debug tools integradas

### Para Usuários
- Interface intuitiva
- Mensagens de ajuda contextuais
- Feedback visual constante
- Documentação de uso disponível

## Troubleshooting

### Problemas Comuns

1. **Campos não aparecem**
   - Verifique se o JavaScript está carregado
   - Confirme que os elementos HTML estão presentes

2. **Validações não funcionam**
   - Verifique a conexão com `cpf-mask.js`
   - Confirme que os event listeners estão ativos

3. **Botões não respondem**
   - Verifique se `window.dynamicFields` está disponível
   - Confirme que a inicialização foi bem-sucedida

### Comandos de Debug

Execute no console do navegador:

```javascript
// Teste completo do sistema
runAllTests();

// Verificar estado atual
window.dynamicFields.debug();

// Adicionar todos os campos para teste
testAddAllFields();

// Remover todos os campos
testRemoveAllFields();

// Verificar elementos necessários
checkRequiredElements();
```

---

**Versão**: 2.0  
**Última Atualização**: Janeiro 2025  
**Autor**: Sistema Nexus  
**Status**: ✅ Totalmente Funcional 