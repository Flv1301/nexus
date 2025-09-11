# Configuração Detalhada para Windows

## 🔧 Configuração do PHP

### 1. Instalação do PHP

**Opção A: Download Manual**
1. Acesse [php.net/downloads](https://www.php.net/downloads.php)
2. Baixe a versão **Thread Safe (TS) x64** do PHP 8.1 ou 8.2
3. Extraia para `C:\php`
4. Adicione `C:\php` ao PATH do sistema:
   - Pressione `Win + R`, digite `sysdm.cpl`
   - Clique em "Variáveis de Ambiente"
   - Em "Variáveis do Sistema", encontre "Path" e clique "Editar"
   - Clique "Novo" e adicione `C:\php`
   - Clique "OK" em todas as janelas

**Opção B: Usando Chocolatey**
```powershell
# Instalar Chocolatey (se não tiver)
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))

# Instalar PHP
choco install php --version=8.1.0
```

### 2. Configuração do php.ini

1. Copie `php.ini-development` para `php.ini` em `C:\php`
2. Edite o arquivo `php.ini` e descomente/adicione:

```ini
; Extensões necessárias
extension=pdo_pgsql
extension=pdo_mysql
extension=mbstring
extension=openssl
extension=tokenizer
extension=xml
extension=ctype
extension=json
extension=bcmath
extension=fileinfo
extension=gd
extension=zip
extension=simplexml
extension=soap
extension=curl
extension=intl

; Configurações de upload
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 512M

; Configurações de sessão
session.save_path = "C:\php\tmp"
```

3. Crie a pasta `C:\php\tmp` para sessões

### 3. Verificação da Instalação

Abra o Prompt de Comando e execute:
```cmd
php -v
php -m
```

## 🗄️ Configuração do PostgreSQL

### 1. Instalação

1. Baixe o PostgreSQL 16 de [postgresql.org/download/windows](https://www.postgresql.org/download/windows/)
2. Execute o instalador
3. Durante a instalação:
   - Escolha "PostgreSQL Server"
   - Defina uma senha para o usuário `postgres`
   - Anote a porta (padrão: 5432)
   - Escolha o locale apropriado

### 2. Configuração do Banco

1. Abra o **pgAdmin** ou use o **psql** via linha de comando
2. Conecte-se ao servidor PostgreSQL
3. Execute os seguintes comandos SQL:

```sql
-- Criar banco de dados
CREATE DATABASE nexus;

-- Criar usuário específico
CREATE USER nexus_user WITH PASSWORD 'sua_senha_segura_aqui';

-- Conceder privilégios
GRANT ALL PRIVILEGES ON DATABASE nexus TO nexus_user;

-- Conectar ao banco nexus
\c nexus

-- Conceder privilégios no schema public
GRANT ALL ON SCHEMA public TO nexus_user;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO nexus_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO nexus_user;
```

### 3. Teste de Conexão

```cmd
psql -h localhost -U nexus_user -d nexus
```

## 🌐 Configuração do Servidor Web

### Opção A: Servidor de Desenvolvimento Laravel

```cmd
php artisan serve --host=0.0.0.0 --port=8000
```

### Opção B: XAMPP

1. Baixe e instale o [XAMPP](https://www.apachefriends.org/)
2. Copie o projeto para `C:\xampp\htdocs\nexus`
3. Configure o Virtual Host:

**httpd-vhosts.conf**:
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/nexus/public"
    ServerName nexus.local
    <Directory "C:/xampp/htdocs/nexus/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**hosts** (C:\Windows\System32\drivers\etc\hosts):
```
127.0.0.1 nexus.local
```

### Opção C: Laragon

1. Baixe e instale o [Laragon](https://laragon.org/)
2. Copie o projeto para `C:\laragon\www\nexus`
3. O Laragon detectará automaticamente o projeto Laravel

## 📁 Estrutura de Permissões

### Pastas que precisam de permissão de escrita:

```cmd
# Dar permissão total às pastas necessárias
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T
icacls public\uploads /grant Everyone:F /T
```

## 🔐 Configuração de Segurança

### 1. Arquivo .env

```env
# Ambiente
APP_ENV=local
APP_DEBUG=true

# Segurança
APP_KEY=base64:sua_chave_gerada_aqui

# Banco de dados
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nexus
DB_USERNAME=nexus_user
DB_PASSWORD=sua_senha_segura

# Cache e Sessão
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail (para desenvolvimento)
MAIL_MAILER=log
```

### 2. Configurações de Produção

Para ambiente de produção, altere:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.com
```

## 🚀 Comandos de Instalação Rápida

### Script Automatizado

Execute o arquivo `install-windows.bat` que foi criado:

```cmd
install-windows.bat
```

### Comandos Manuais

```cmd
# 1. Instalar dependências
composer install --no-dev --optimize-autoloader
npm install

# 2. Configurar ambiente
copy .env.example .env
php artisan key:generate

# 3. Executar migrações
php artisan migrate

# 4. Compilar assets
npm run dev

# 5. Iniciar servidor
php artisan serve
```

## 🔍 Verificação de Requisitos

Execute o script PowerShell para verificar se tudo está configurado:

```powershell
PowerShell -ExecutionPolicy Bypass -File check-requirements.ps1
```

## 🐛 Solução de Problemas

### Erro: "Class 'PDO' not found"
- Verifique se a extensão `pdo_pgsql` está habilitada no `php.ini`

### Erro: "Permission denied"
- Execute o comando de permissões:
```cmd
icacls storage /grant Everyone:F /T
icacls bootstrap\cache /grant Everyone:F /T
```

### Erro: "Connection refused" (PostgreSQL)
- Verifique se o PostgreSQL está rodando
- Confirme a porta e credenciais no `.env`

### Erro: "Module not found" (Node.js)
- Execute `npm install` novamente
- Verifique se o Node.js está no PATH

### Assets não carregam
- Execute `npm run dev` ou `npm run production`
- Verifique se o Laravel Mix está funcionando

## 📊 Monitoramento

### Logs do Laravel
```cmd
# Ver logs em tempo real
tail -f storage\logs\laravel.log
```

### Logs do PostgreSQL
- Localização: `C:\Program Files\PostgreSQL\16\data\log\`

### Logs do Apache (XAMPP)
- Localização: `C:\xampp\apache\logs\error.log`

## 🔄 Atualizações

### Atualizar dependências PHP
```cmd
composer update
```

### Atualizar dependências Node.js
```cmd
npm update
```

### Executar migrações pendentes
```cmd
php artisan migrate
```

### Limpar cache
```cmd
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

**Nota**: Este guia assume que você tem privilégios de administrador no Windows. Algumas configurações podem requerer execução como administrador.


