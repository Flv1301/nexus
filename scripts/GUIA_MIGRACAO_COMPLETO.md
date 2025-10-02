# 🔄 GUIA COMPLETO DE MIGRAÇÃO - SISTEMA NEXUS

## 📋 Resumo
Este guia detalha como fazer backup completo dos dados do sistema Nexus em produção na AWS e restaurar em um novo ambiente.

## 🎯 O que será migrado
- ✅ **Banco de dados PostgreSQL** (todas as tabelas com dados)
- ✅ **Arquivos de casos** (PDFs, imagens, documentos)
- ✅ **Fotos de pessoas** 
- ✅ **Documentos públicos**
- ✅ **Configurações essenciais**

---

## 🚀 FASE 1: PREPARAÇÃO DO BACKUP NA AWS

### 1.1 Conectar na instância AWS
```bash
# SSH na sua instância AWS
ssh -i sua-chave.pem ubuntu@seu-ip-aws
```

### 1.2 Preparar ambiente
```bash
# Navegar para o diretório da aplicação
cd /var/www/nexus

# Verificar espaço em disco
df -h

# Verificar se tem PostgreSQL client
pg_dump --version
```

### 1.3 Configurar variáveis de ambiente
```bash
# Exportar variáveis do banco (ajuste conforme seu .env)
export DB_HOST="localhost"
export DB_PORT="5432"
export DB_DATABASE="nome_do_banco"
export DB_USERNAME="usuario_postgres"
export DB_PASSWORD="senha_postgres"
```

---

## 📦 FASE 2: EXECUTAR BACKUP

### Opção A: Backup Completo (Recomendado)
```bash
# Dar permissão ao script
chmod +x scripts/backup-production-aws.sh

# Executar backup completo
./scripts/backup-production-aws.sh
```

### Opção B: Backup Somente Dados Essenciais (Mais Rápido)
```bash
# Dar permissão ao script
chmod +x scripts/backup-data-only.sh

# Executar backup de dados
./scripts/backup-data-only.sh
```

### 2.1 O que cada backup inclui:

#### Backup Completo:
- Banco PostgreSQL (dados + estrutura)
- Todos os arquivos do storage/
- Arquivos públicos relevantes
- Configurações (.env, config/)
- Scripts de restauração
- Relatório detalhado

#### Backup Dados Essenciais:
- Banco PostgreSQL (formato custom)
- Arquivos de casos (storage/app/case/)
- Imagens (storage/app/images/)
- Documentos (storage/app/public/docs/)
- Script de restauração simplificado

---

## 📤 FASE 3: TRANSFERIR BACKUP

### 3.1 Download do backup
```bash
# Na sua máquina local, baixar o backup
scp -i sua-chave.pem ubuntu@seu-ip-aws:/tmp/backup_*.tar.gz ./
```

### 3.2 Verificar integridade
```bash
# Verificar se o arquivo não está corrompido
tar -tzf backup_*.tar.gz > /dev/null && echo "✓ Arquivo íntegro" || echo "✗ Arquivo corrompido"
```

---

## 🏗️ FASE 4: PREPARAR NOVO AMBIENTE

### 4.1 Instalar dependências no novo servidor
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install postgresql postgresql-client php php-pgsql nginx

# CentOS/RHEL
sudo yum install postgresql postgresql-server php php-pgsql nginx
```

### 4.2 Configurar PostgreSQL
```bash
# Iniciar PostgreSQL
sudo systemctl start postgresql
sudo systemctl enable postgresql

# Criar banco e usuário
sudo -u postgres psql
```

```sql
-- No console do PostgreSQL
CREATE DATABASE nexus_novo;
CREATE USER nexus_user WITH PASSWORD 'nova_senha_segura';
GRANT ALL PRIVILEGES ON DATABASE nexus_novo TO nexus_user;
\q
```

### 4.3 Configurar diretórios da aplicação
```bash
# Criar estrutura
sudo mkdir -p /var/www/novo_sistema
sudo chown -R www-data:www-data /var/www/novo_sistema
```

---

## 🔄 FASE 5: RESTAURAR DADOS

### 5.1 Extrair backup
```bash
# Extrair arquivo de backup
tar -xzf backup_*.tar.gz
cd backup_*/
```

### 5.2 Restaurar banco de dados

#### Para Backup Completo (.sql):
```bash
# Editar script de restauração
nano restore_database.sh

# Ajustar variáveis:
NEW_DB_HOST="localhost"
NEW_DB_PORT="5432"
NEW_DB_NAME="nexus_novo"
NEW_DB_USER="nexus_user"
NEW_DB_PASSWORD="nova_senha_segura"

# Executar restauração
./restore_database.sh
```

#### Para Backup Dados Essenciais (.dump):
```bash
# Editar script de restauração
nano restaurar_dados.sh

# Ajustar variáveis e executar
./restaurar_dados.sh
```

### 5.3 Restaurar arquivos
```bash
# Se usando backup completo
./migrate_files.sh

# Ou manualmente
sudo cp -r storage/* /var/www/novo_sistema/storage/
sudo cp -r public/* /var/www/novo_sistema/public/
sudo chown -R www-data:www-data /var/www/novo_sistema/storage
```

---

## ⚙️ FASE 6: CONFIGURAR NOVA APLICAÇÃO

### 6.1 Configurar .env
```bash
# Copiar e editar arquivo .env
cp .env /var/www/novo_sistema/
nano /var/www/novo_sistema/.env
```

```env
# Principais configurações a ajustar:
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=nexus_novo
DB_USERNAME=nexus_user
DB_PASSWORD=nova_senha_segura

APP_URL=http://seu-novo-dominio.com
APP_KEY=base64:... # Gerar nova chave
```

### 6.2 Finalizar configuração Laravel
```bash
cd /var/www/novo_sistema

# Instalar dependências
composer install --no-dev --optimize-autoloader

# Gerar chave da aplicação
php artisan key:generate

# Criar link simbólico para storage
php artisan storage:link

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Ajustar permissões
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache
```

---

## 🔍 FASE 7: VERIFICAÇÃO E TESTES

### 7.1 Testar conexão com banco
```bash
php artisan migrate:status
```

### 7.2 Verificar arquivos
```bash
# Contar arquivos migrados
find storage/app -type f | wc -l
find storage/app/case -name "*.pdf" | wc -l
find storage/app/images -name "*.jpg" -o -name "*.png" | wc -l
```

### 7.3 Teste funcional
- Acessar aplicação via navegador
- Tentar login
- Verificar se imagens carregam
- Testar upload de arquivos
- Verificar relatórios

---

## 🛠️ RESOLUÇÃO DE PROBLEMAS

### Problema: Erro de conexão com banco
```bash
# Verificar se PostgreSQL está rodando
sudo systemctl status postgresql

# Verificar logs
sudo tail -f /var/log/postgresql/postgresql-*.log
```

### Problema: Arquivos não aparecem
```bash
# Verificar link simbólico
ls -la public/storage

# Recriar se necessário
php artisan storage:link
```

### Problema: Permissões
```bash
# Ajustar permissões
sudo chown -R www-data:www-data /var/www/novo_sistema
sudo chmod -R 755 storage
sudo chmod -R 755 bootstrap/cache
```

### Problema: Erro 500
```bash
# Verificar logs do Laravel
tail -f storage/logs/laravel.log

# Verificar logs do web server
sudo tail -f /var/log/nginx/error.log
```

---

## 📊 CHECKLIST FINAL

### ✅ Verificações Obrigatórias:
- [ ] Banco de dados acessível e com dados
- [ ] Arquivos de casos acessíveis
- [ ] Imagens carregando corretamente
- [ ] Login funcionando
- [ ] Upload de arquivos funcionando
- [ ] Permissões corretas nos diretórios
- [ ] SSL/HTTPS configurado (se necessário)
- [ ] Backup do ambiente antigo mantido

### 🔧 Configurações Adicionais:
- [ ] Configurar cron jobs (se houver)
- [ ] Configurar monitoramento
- [ ] Configurar backup automático
- [ ] Documentar credenciais do novo ambiente
- [ ] Atualizar DNS (se mudou domínio)

---

## 📞 SUPORTE

Se encontrar problemas durante a migração:

1. **Verifique os logs**: `storage/logs/laravel.log`
2. **Teste conexões**: Banco, arquivos, permissões
3. **Compare dados**: Contagem de registros antes/depois
4. **Valide funcionalidades**: Login, upload, relatórios

---

## 🎉 MIGRAÇÃO CONCLUÍDA!

Após seguir todos os passos, sua migração estará completa. Lembre-se de:

- Manter backup do ambiente original por alguns dias
- Monitorar o novo ambiente nas primeiras horas
- Testar todas as funcionalidades principais
- Atualizar documentação interna com novas configurações

**Boa sorte com sua migração! 🚀**


