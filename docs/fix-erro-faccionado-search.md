# Correção do Erro de Visualização de Dados Faccionados

## Problema Identificado
Erro `Call to undefined method App\Services\PersonSearchService::faccionado()` ao clicar no botão de visualizar (👁️) de uma pessoa na busca por "faccionados".

### URL que Causava o Erro
```
http://mpe.dev.com.br/pesquisa/pessoa/faccionado/120
```

### Stack Trace
```
App\Http\Controllers\Search\PersonSearchController::show
app/Http/Controllers/Search/PersonSearchController.php:443

$person = $service->$base($id);
```

## Causa Raiz

O problema ocorreu após a criação da nova aba "Facção". O sistema de busca possui uma estrutura onde:

1. **Busca por faccionados**: Método `faccionado(Request $request)` no `PersonSearchController`
2. **Visualização individual**: Rota `/pesquisa/pessoa/{base}/{id}` que chama `$service->$base($id)`

Quando o usuário clicava no ícone 👁️ de uma pessoa "faccionada", o sistema tentava chamar `PersonSearchService::faccionado($id)`, mas esse método não existia.

## Estrutura do Problema

### Fluxo Incorreto (Antes da Correção)
```
Busca → Lista faccionados → Clique 👁️ → /pesquisa/pessoa/faccionado/120 → PersonSearchService::faccionado($id) → ERRO!
```

### Componente Problemático
**Arquivo**: `resources/views/components/show-bases-persons.blade.php`
**Linha**: 38

```php
// ANTES (gerava erro)
<a href="{{route($route, ['base'=> $key, 'id' => $person->id])}}">
    <i class="fas fa-lg fa-eye text-primary"></i>
</a>
```

## Solução Implementada

### 1. **Redirecionamento Inteligente**
Modificado o componente `show-bases-persons.blade.php` para detectar quando a base é "faccionado" e redirecionar para a visualização normal da pessoa.

```php
// DEPOIS (funciona corretamente)
@if($key === 'faccionado')
    {{-- Para dados faccionados, redireciona para visualização normal da pessoa --}}
    <a href="{{route('person.show', $person->id)}}" class="mr-2" title="Visualizar Dados">
        <i class="fas fa-lg fa-eye text-primary"></i>
    </a>
@else
    {{-- Para outras bases, usa a rota de busca normal --}}
    <a href="{{route($route, ['base'=> $key, 'id' => $person->id])}}" class="mr-2" title="Visualizar Dados">
        <i class="fas fa-lg fa-eye text-primary"></i>
    </a>
@endif
```

### 2. **Fluxo Correto (Após Correção)**
```
Busca → Lista faccionados → Clique 👁️ → /pessoa/dados/120 → Visualização normal com aba Facção → ✅ SUCESSO!
```

## Arquivos Modificados

### ✅ **Corrigido**
- `resources/views/components/show-bases-persons.blade.php` - Redirecionamento inteligente

### ❌ **Descartado**
- ~~`app/Services/PersonSearchService.php`~~ - Método `faccionado($id)` não é necessário

## Benefícios da Solução

### 1. **Correção do Erro**
- ✅ Elimina o erro `Call to undefined method`
- ✅ Funcionalidade de visualização totalmente operacional

### 2. **Experiência Consistente**
- ✅ Usuários visualizam dados faccionados na interface padrão
- ✅ Acesso à nova aba "Facção" criada anteriormente
- ✅ Controle de permissões `@can('sisfac')` mantido

### 3. **Manutenibilidade**
- ✅ Solução simples e direta
- ✅ Não quebra funcionalidades existentes
- ✅ Fácil de entender e manter

## Funcionalidade Após Correção

### Para Usuários com Permissão `sisfac`
1. **Busca por faccionados** → Lista pessoas ativas
2. **Clique no ícone 👁️** → Redireciona para `/pessoa/dados/{id}`
3. **Visualização completa** → Acesso a todas as abas, incluindo "Facção"
4. **Aba Facção** → Dados específicos de organização criminosa

### Para Usuários sem Permissão `sisfac`
1. **Busca por faccionados** → Não disponível (checkbox não aparece)
2. **Visualização de pessoa** → Aba "Facção" não aparece
3. **Dados faccionados** → Ocultos/restritos conforme permissões

## Compatibilidade

### ✅ **Mantida**
- Todas as funcionalidades existentes de busca
- Compatibilidade com outras bases (nexus, cortex, etc.)
- Sistema de permissões inalterado
- Relatórios PDF funcionais

### ✅ **Aprimorada**
- Visualização de dados faccionados agora funcional
- Integração perfeita com nova aba "Facção"
- Experiência do usuário mais consistente

---

**Data da Correção**: {{ date('Y-m-d H:i:s') }}
**Arquivo Principal Modificado**: `resources/views/components/show-bases-persons.blade.php`
**Status**: ✅ Erro Corrigido - Funcionalidade Totalmente Operacional 