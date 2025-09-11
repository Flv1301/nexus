# Guia de Instalação do Nexus - Windows

Este guia detalha como instalar e configurar o projeto Nexus em uma máquina Windows sem usar Docker.

## 📋 Pré-requisitos

### 1. Software Necessário

#### PHP 8.1 ou superior
- **Download**: [php.net/downloads](https://www.php.net/downloads.php)
- **Versão recomendada**: PHP 8.1.x ou 8.2.x
- **Extensões necessárias**:
  - `ext-simplexml`
  - `ext-soap`
  - `pdo_pgsql` (para PostgreSQL)
  - `pdo_mysql` (alternativa)
  - `mbstring`
  - `openssl`
  - `tokenizer`
  - `xml`
  - `ctype`
  - `json`
  - `bcmath`
  - `fileinfo`
  - `gd` ou `imagick`
  - `zip`

#### Composer
- **Download**: [getcomposer.org](https://getcomposer.org/download/)
- **Instalação**: Execute o instalador Windows

#### Node.js e NPM
- **Download**: [nodejs.org](https://nodejs.org/)
- **Versão recomendada**: Node.js 16.x ou superior
- **NPM**: Vem incluído com Node.js

#### PostgreSQL 16
- **Download**: [postgresql.org/download/windows](https://www.postgresql.org/download/windows/)
- **Versão**: PostgreSQL 16 (conforme especificado no README)
- **Alternativa**: MySQL 8.0+ ou MariaDB 10.3+

#### Servidor Web (opcional para desenvolvimento)
- **XAMPP**: [apachefriends.org](https://www.apachefriends.org/)
- **WAMP**: [wampserver.com](https://www.wampserver.com/)
- **Laragon**: [laragon.org](https://laragon.org/)

## 🚀 Processo de Instalação

### Passo 1: Configurar PHP

1. **Instalar PHP**:
   - Baixe a versão Thread Safe do PHP
   - Extraia para `C:\php`
   - Adicione `C:\php` ao PATH do sistema

2. **Configurar php.ini**:
   ```ini
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
   ```

3. **Verificar instalação**:
   ```cmd
   php -v
   php -m
   ```

### Passo 2: Instalar Composer

1. Baixe e execute o instalador do Composer
2. Verifique a instalação:
   ```cmd
   composer --version
   ```

### Passo 3: Instalar Node.js e NPM

1. Baixe e instale o Node.js
2. Verifique a instalação:
   ```cmd
   node --version
   npm --version
   ```

### Passo 4: Configurar PostgreSQL

1. **Instalar PostgreSQL 16**:
   - Execute o instalador
   - Configure senha para usuário `postgres`
   - Anote a porta (padrão: 5432)

2. **Criar banco de dados**:
   ```sql
   CREATE DATABASE nexus;
   CREATE USER nexus_user WITH PASSWORD 'sua_senha_aqui';
   GRANT ALL PRIVILEGES ON DATABASE nexus TO nexus_user;
   ```

### Passo 5: Configurar o Projeto

1. **Navegar para o diretório do projeto**:
   ```cmd
   cd C:\Users\Flavio\Desktop\nexus
   ```

2. **Instalar dependências PHP**:
   ```cmd
   composer install
   ```

3. **Instalar dependências Node.js**:
   ```cmd
   npm install
   ```

4. **Criar arquivo de configuração**:
   ```cmd
   copy .env.example .env
   ```
   
   **Se não existir .env.example, crie um arquivo .env com o seguinte conteúdo**:
   ```env
   APP_NAME=Nexus
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost

   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=debug

   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=nexus
   DB_USERNAME=nexus_user
   DB_PASSWORD=sua_senha_aqui

   BROADCAST_DRIVER=log
   CACHE_DRIVER=file
   FILESYSTEM_DISK=local
   QUEUE_CONNECTION=sync
   SESSION_DRIVER=file
   SESSION_LIFETIME=120

   MEMCACHED_HOST=127.0.0.1

   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379

   MAIL_MAILER=smtp
   MAIL_HOST=mailpit
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"

   AWS_ACCESS_KEY_ID=
   AWS_SECRET_ACCESS_KEY=
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=
   AWS_USE_PATH_STYLE_ENDPOINT=false

   PUSHER_APP_ID=
   PUSHER_APP_KEY=
   PUSHER_APP_SECRET=
   PUSHER_HOST=
   PUSHER_PORT=443
   PUSHER_SCHEME=https
   PUSHER_APP_CLUSTER=mt1

   VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
   VITE_PUSHER_HOST="${PUSHER_HOST}"
   VITE_PUSHER_PORT="${PUSHER_PORT}"
   VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
   VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
   ```

5. **Gerar chave da aplicação**:
   ```cmd
   php artisan key:generate
   ```

6. **Executar migrações**:
   ```cmd
   php artisan migrate
   ```

7. **Compilar assets**:
   ```cmd
   npm run dev
   ```
   
   **Para produção**:
   ```cmd
   npm run production
   ```

### Passo 6: Configurar Servidor Web

#### Opção A: Usando o servidor de desenvolvimento do Laravel
```cmd
php artisan serve
```
Acesse: `http://localhost:8000`

#### Opção B: Usando Apache/Nginx

**Apache (XAMPP/WAMP)**:
1. Copie o projeto para `htdocs` (XAMPP) ou `www` (WAMP)
2. Configure Virtual Host:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/Users/Flavio/Desktop/nexus/public"
       ServerName nexus.local
       <Directory "C:/Users/Flavio/Desktop/nexus/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
3. Adicione ao arquivo `hosts`:
   ```
   127.0.0.1 nexus.local
   ```

**Nginx**:
```nginx
server {
    listen 80;
    server_name nexus.local;
    root C:/Users/Flavio/Desktop/nexus/public;
    
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    
    index index.php;
    
    charset utf-8;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    error_page 404 /index.php;
    
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔧 Configurações Adicionais

### Configurar Permissões (se necessário)
```cmd
# Para Windows, geralmente não é necessário, mas se houver problemas:
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T
```

### Configurar Queue (opcional)
Para processar filas em background:
```cmd
php artisan queue:work
```

### Configurar Cache
```cmd
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🐛 Solução de Problemas Comuns

### Erro de permissão
- Verifique se o PHP tem permissão para escrever nas pastas `storage` e `bootstrap/cache`

### Erro de extensão PHP
- Verifique se todas as extensões necessárias estão habilitadas no `php.ini`

### Erro de banco de dados
- Verifique se o PostgreSQL está rodando
- Confirme as credenciais no arquivo `.env`
- Teste a conexão:
  ```cmd
  php artisan tinker
  DB::connection()->getPdo();
  ```

### Erro de assets não carregando
- Execute `npm run dev` ou `npm run production`
- Verifique se o Laravel Mix está funcionando:
  ```cmd
  npm run watch
  ```

### Erro 500
- Verifique os logs em `storage/logs/laravel.log`
- Execute `php artisan config:clear`
- Execute `php artisan cache:clear`

## 📝 Comandos Úteis

```cmd
# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar autoload
composer dump-autoload

# Executar testes
php artisan test

# Verificar rotas
php artisan route:list

# Verificar configuração
php artisan config:show
```

## 🔒 Configurações de Segurança

1. **Alterar APP_KEY** após instalação
2. **Configurar APP_ENV=production** em produção
3. **Configurar APP_DEBUG=false** em produção
4. **Usar HTTPS** em produção
5. **Configurar firewall** adequadamente

## 📞 Suporte

Em caso de problemas:
1. Verifique os logs em `storage/logs/`
2. Consulte a documentação do Laravel
3. Verifique se todas as dependências estão instaladas corretamente

---

**Nota**: Este guia assume que você tem conhecimento básico de PHP, Laravel e administração de sistemas Windows. Para ambientes de produção, considere usar um servidor Linux com configurações mais robustas.


