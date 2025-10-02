# Checklist de Debug - Remoção de Documentos

## 🔍 Problema
Documentos não estão sendo apagados quando o botão lixeira é clicado.

## ✅ Implementações Feitas

### 1. JavaScript (Frontend)
- ✅ Array `removedDocs[]` para rastrear IDs removidos
- ✅ Função `removeExistingDoc(element, docId)` 
- ✅ Função `updateRemovedDocsField()` para criar campo hidden
- ✅ Campo `removed_docs` sendo adicionado ao formulário

### 2. Controller (Backend)
- ✅ Método `removeSpecificDocs()` implementado
- ✅ Processamento de `removed_docs` no método `update`
- ✅ Logs de debug adicionados
- ✅ Remoção de arquivos físicos

## 🧪 Etapas de Teste

### Teste 1: Verificar JavaScript
1. Abrir `test-docs-functionality.html`
2. Clicar em "remover" em um documento existente
3. Clicar em "Debug Form Data"
4. **Verificar**: Campo `removed_docs` deve aparecer com o ID do documento

### Teste 2: Verificar Envio do Formulário
1. Ir para edição de uma pessoa com documentos
2. Remover um documento (clicar na lixeira)
3. Salvar o formulário
4. **Verificar logs**: `/var/www/nexus/storage/logs/laravel.log`

### Teste 3: Logs Esperados
```
[timestamp] local.INFO: Update request data received: {"has_removed_docs":true,"removed_docs":"[1,2]",...}
[timestamp] local.INFO: removeSpecificDocs called: {"person_id":123,"removedDocs_type":"string","removedDocs_value":"[1,2]"}
[timestamp] local.INFO: Document found, preparing to delete: {"id":1,"nome_doc":"..."}
[timestamp] local.INFO: Doc removed successfully: {"doc_id":1}
```

## 🔧 Possíveis Problemas

### 1. JavaScript não executa
**Sintomas**: Array `removedDocs` vazio no debug
**Solução**: Verificar console do navegador para erros

### 2. Campo não enviado no formulário
**Sintomas**: `has_removed_docs` = false nos logs
**Solução**: Verificar se campo está dentro do formulário correto

### 3. Método não chamado
**Sintomas**: Log "removeSpecificDocs called" não aparece
**Solução**: Verificar condição `$request->has('removed_docs')`

### 4. Documento não encontrado
**Sintomas**: Log "Doc not found" aparece
**Solução**: Verificar se ID do documento está correto

## 🚀 Comandos de Debug

### Ver logs em tempo real:
```bash
cd /var/www/nexus
tail -f storage/logs/laravel.log | grep -i "removed\|docs"
```

### Verificar diretório de uploads:
```bash
ls -la public/uploads/docs/
```

### Limpar logs para teste:
```bash
> storage/logs/laravel.log
```

## 📝 Status Atual
- ✅ Frontend implementado
- ✅ Backend implementado  
- ✅ Logs de debug adicionados
- 🔄 **Testando funcionalidade**

---

**Próximo Passo**: Executar os testes acima e verificar logs para identificar onde está falhando. 