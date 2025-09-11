# Instalação de Extensões PHP - Windows

Este guia mostra como instalar as extensões PHP necessárias para o projeto Nexus no Windows.

## 📋 Extensões Necessárias

- **intl** - Internacionalização
- **soap** - Web Services SOAP
- **zip** - Manipulação de arquivos ZIP
- **gd** - Manipulação de imagens
- **fileinfo** - Detecção de tipos de arquivo
- **pdo_pgsql** - Driver PostgreSQL para PDO

## 🔧 Método 1: Usando XAMPP (Mais Fácil)

### 1. Baixar e Instalar XAMPP
1. Acesse [apachefriends.org](https://www.apachefriends.org/)
2. Baixe a versão mais recente do XAMPP
3. Execute o instalador como administrador
4. Instale com Apache, MySQL, PHP e phpMyAdmin

### 2. Habilitar Extensões no XAMPP
1. Abra o arquivo `C:\xampp\php\php.ini`
2. Procure pelas extensões e descomente (remova o `;`):

```ini
; Extensões necessárias
extension=intl
extension=soap
extension=zip
extension=gd
extension=fileinfo
extension=pdo_pgsql
```

3. Salve o arquivo
4. Reinicie o Apache no painel do XAMPP

## 🔧 Método 2: Instalação Manual do PHP

### 1. Baixar PHP
1. Acesse [php.net/downloads](https://www.php.net/downloads.php)
2. Baixe a versão **Thread Safe (TS) x64** do PHP 8.1 ou 8.2
3. Extraia para `C:\php`

### 2. Configurar php.ini
1. Copie `php.ini-development` para `php.ini` em `C:\php`
2. Edite o arquivo `php.ini` e descomente/adicione:

```ini
; Extensões necessárias
extension=intl
extension=soap
extension=zip
extension=gd
extension=fileinfo
extension=pdo_pgsql

; Configurações adicionais
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 512M
```

### 3. Adicionar ao PATH
1. Pressione `Win + R`, digite `sysdm.cpl`
2. Clique em "Variáveis de Ambiente"
3. Em "Variáveis do Sistema", encontre "Path" e clique "Editar"
4. Clique "Novo" e adicione `C:\php`
5. Clique "OK" em todas as janelas

## 🔧 Método 3: Usando Chocolatey

### 1. Instalar Chocolatey (se não tiver)
```powershell
# Execute no PowerShell como administrador
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))
```

### 2. Instalar PHP com Extensões
```powershell
# Instalar PHP
choco install php --version=8.1.0

# Instalar PostgreSQL (para pdo_pgsql)
choco install postgresql
```

### 3. Configurar Extensões
1. Edite `C:\tools\php\php.ini`
2. Descomente as extensões necessárias

## 🔧 Método 4: Usando Laragon

### 1. Baixar e Instalar Laragon
1. Acesse [laragon.org](https://laragon.org/)
2. Baixe a versão Full
3. Execute o instalador

### 2. Habilitar Extensões
1. Abra o Laragon
2. Clique com botão direito no ícone do Laragon
3. Vá em "PHP" > "php.ini"
4. Descomente as extensões necessárias
5. Reinicie o Laragon

## 🔧 Método 5: Usando WAMP

### 1. Baixar e Instalar WAMP
1. Acesse [wampserver.com](https://www.wampserver.com/)
2. Baixe a versão 64-bit
3. Execute o instalador

### 2. Habilitar Extensões
1. Clique no ícone do WAMP na bandeja do sistema
2. Vá em "PHP" > "PHP Extensions"
3. Marque as extensões necessárias:
   - php_intl
   - php_soap
   - php_zip
   - php_gd2
   - php_fileinfo
   - php_pdo_pgsql

## ✅ Verificação da Instalação

### 1. Verificar PHP
```cmd
php -v
```

### 2. Verificar Extensões
```cmd
php -m
```

### 3. Verificar Extensões Específicas
```cmd
php -m | findstr "intl soap zip gd fileinfo pdo_pgsql"
```

### 4. Criar Script de Teste
Crie um arquivo `test-extensions.php`:

```php
<?php
echo "=== VERIFICAÇÃO DE EXTENSÕES PHP ===\n\n";

$extensions = [
    'intl' => 'Internacionalização',
    'soap' => 'Web Services SOAP',
    'zip' => 'Manipulação de arquivos ZIP',
    'gd' => 'Manipulação de imagens',
    'fileinfo' => 'Detecção de tipos de arquivo',
    'pdo_pgsql' => 'Driver PostgreSQL para PDO'
];

foreach ($extensions as $ext => $desc) {
    if (extension_loaded($ext)) {
        echo "✅ $ext - $desc (OK)\n";
    } else {
        echo "❌ $ext - $desc (FALTANDO)\n";
    }
}

echo "\n=== INFORMAÇÕES DO PHP ===\n";
echo "Versão: " . PHP_VERSION . "\n";
echo "SAPI: " . php_sapi_name() . "\n";
echo "Arquivo php.ini: " . php_ini_loaded_file() . "\n";
?>
```

Execute:
```cmd
php test-extensions.php
```

## 🐛 Solução de Problemas

### Erro: "Unable to load dynamic library"
- Verifique se o arquivo `.dll` da extensão existe em `C:\php\ext\`
- Confirme se o caminho `extension_dir` está correto no `php.ini`

### Erro: "Class 'PDO' not found"
- Verifique se `extension=pdo` está habilitado
- Confirme se `extension=pdo_pgsql` está habilitado

### Erro: "Call to undefined function"
- Verifique se a extensão está carregada: `php -m`
- Confirme se não há erro de sintaxe no `php.ini`

### Extensões não aparecem após reiniciar
- Verifique se o `php.ini` correto está sendo usado
- Confirme se não há múltiplas instalações do PHP
- Use `php --ini` para ver qual arquivo está sendo carregado

## 📝 Configuração Recomendada do php.ini

```ini
; Extensões essenciais
extension=curl
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=openssl
extension=pdo
extension=pdo_pgsql
extension=soap
extension=zip
extension=json
extension=tokenizer
extension=xml
extension=ctype
extension=bcmath

; Configurações de upload
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
memory_limit = 512M

; Configurações de sessão
session.save_path = "C:\php\tmp"
session.gc_maxlifetime = 1440

; Configurações de timezone
date.timezone = "America/Sao_Paulo"

; Configurações de erro (desenvolvimento)
display_errors = On
error_reporting = E_ALL
log_errors = On
error_log = "C:\php\logs\php_errors.log"
```

## 🚀 Próximos Passos

Após instalar as extensões:

1. **Teste o projeto Nexus**:
   ```cmd
   php artisan --version
   ```

2. **Execute as migrações**:
   ```cmd
   php artisan migrate
   ```

3. **Inicie o servidor**:
   ```cmd
   php artisan serve
   ```

## 📞 Suporte

Se encontrar problemas:

1. Verifique os logs do PHP em `C:\php\logs\`
2. Consulte a documentação oficial do PHP
3. Verifique se todas as dependências estão instaladas
4. Teste com um script PHP simples primeiro

---

**Nota**: Este guia assume que você tem privilégios de administrador no Windows. Algumas instalações podem requerer execução como administrador.


