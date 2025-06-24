# Sistema de Análise WhatsApp

## Descrição
Sistema para upload e análise de arquivos HTML exportados do WhatsApp, com extração de IPs, números de telefone, timestamps e mensagens.

## Funcionalidades Implementadas

### 1. Upload de Arquivos
- **Localização**: Menu CADASTRO > WhatsApp Analysis
- **Tipos aceitos**: .html, .htm
- **Tamanho máximo**: 10MB
- **Campos obrigatórios**: Nome da análise e arquivo

### 2. Processamento Automático
- **Extração de mensagens**: Até 100 mensagens mais recentes
- **Identificação de IPs**: Resolução automática de geolocalização
- **Números de telefone**: Extração com padrões brasileiros e internacionais
- **Timestamps**: Análise de datas e horários das conversas

### 3. Relatório de Análise
- **Estatísticas gerais**: Total de mensagens, IPs únicos, telefones únicos
- **Período da conversa**: Data/hora início e fim
- **Lista de IPs**: Com geolocalização (quando disponível)
- **Números identificados**: Formatação organizada
- **Amostra de mensagens**: Preview das conversas

## Configuração Necessária

### 1. Permissões
Execute a migration para criar as permissões:
```bash
php artisan migrate
```

### 2. Atribuir Permissões aos Usuários
As seguintes permissões foram criadas:
- `whatsapp` - Acesso geral ao módulo
- `whatsapp.ler` - Visualizar análises
- `whatsapp.cadastrar` - Criar novas análises
- `whatsapp.excluir` - Remover análises
- `whatsapp.atualizar` - Editar análises

### 3. Menu AdminLTE
A nova aba foi adicionada automaticamente ao menu CADASTRO.

## Como Usar

### 1. Acesso
- Faça login no sistema
- Acesse: **CADASTRO > WhatsApp Analysis**

### 2. Upload
1. Digite um nome para a análise
2. Selecione o arquivo HTML do WhatsApp
3. Clique em "Processar Arquivo"

### 3. Visualização
- O sistema redirecionará automaticamente para o relatório
- Use "Imprimir Relatório" para salvar em PDF
- Use "Nova Análise" para processar outro arquivo

## Integração com APIs

### IPInfo Service
O sistema integra automaticamente com o serviço IPInfo para:
- Geolocalização de IPs
- Informações de provedor
- Cache de 7 dias para otimização

### WhatsApp Event System
- Processamento assíncrono via eventos
- Logs automáticos de atividades
- Integração com sistema de casos (opcional)

## Armazenamento
- **Arquivos**: `storage/app/whatsapp_analyses/`
- **Dados**: Sessão temporária (pode ser migrado para banco)
- **Logs**: Sistema padrão do Laravel

## Segurança
- Middleware de autenticação obrigatório
- Verificação de permissões por usuário
- Validação de tipos de arquivo
- Sanitização de dados HTML

## Próximos Passos (Opcional)
1. Criar model `WhatsappAnalysis` para persistência
2. Implementar histórico de análises por usuário
3. Adicionar exportação em diferentes formatos
4. Melhorar parsing para diferentes versões do WhatsApp
5. Integração com sistema de casos existente 