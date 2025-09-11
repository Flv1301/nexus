@echo off
echo ========================================
echo    INSTALACAO DE EXTENSOES PHP
echo ========================================
echo.

REM Verificar se o PHP está instalado
echo [1/4] Verificando PHP...
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERRO: PHP nao encontrado.
    echo.
    echo OPCOES DE INSTALACAO:
    echo 1. XAMPP: https://www.apachefriends.org/
    echo 2. WAMP: https://www.wampserver.com/
    echo 3. Laragon: https://laragon.org/
    echo 4. Chocolatey: choco install php
    echo.
    pause
    exit /b 1
)
echo PHP encontrado!

REM Verificar arquivo php.ini
echo [2/4] Localizando arquivo php.ini...
php --ini > temp_ini.txt 2>&1
findstr "Loaded Configuration File" temp_ini.txt >nul
if %errorlevel% neq 0 (
    echo ERRO: Arquivo php.ini nao encontrado.
    del temp_ini.txt
    pause
    exit /b 1
)

for /f "tokens=2 delims=:" %%i in ('findstr "Loaded Configuration File" temp_ini.txt') do set INI_FILE=%%i
set INI_FILE=%INI_FILE: =%
echo Arquivo php.ini encontrado: %INI_FILE%
del temp_ini.txt

REM Verificar se o arquivo existe
if not exist "%INI_FILE%" (
    echo ERRO: Arquivo php.ini nao existe: %INI_FILE%
    pause
    exit /b 1
)

REM Fazer backup do php.ini
echo [3/4] Fazendo backup do php.ini...
copy "%INI_FILE%" "%INI_FILE%.backup.%date:~-4,4%%date:~-10,2%%date:~-7,2%" >nul
echo Backup criado: %INI_FILE%.backup.%date:~-4,4%%date:~-10,2%%date:~-7,2%

REM Verificar extensões atuais
echo [4/4] Verificando extensoes atuais...
echo.
echo EXTENSOES NECESSARIAS PARA O NEXUS:
echo.

set EXTENSIONS_OK=1

REM Verificar intl
php -m | findstr "^intl$" >nul
if %errorlevel% equ 0 (
    echo ✓ intl - Internacionalizacao (OK)
) else (
    echo ✗ intl - Internacionalizacao (FALTANDO)
    set EXTENSIONS_OK=0
)

REM Verificar soap
php -m | findstr "^soap$" >nul
if %errorlevel% equ 0 (
    echo ✓ soap - Web Services SOAP (OK)
) else (
    echo ✗ soap - Web Services SOAP (FALTANDO)
    set EXTENSIONS_OK=0
)

REM Verificar zip
php -m | findstr "^zip$" >nul
if %errorlevel% equ 0 (
    echo ✓ zip - Manipulacao de arquivos ZIP (OK)
) else (
    echo ✗ zip - Manipulacao de arquivos ZIP (FALTANDO)
    set EXTENSIONS_OK=0
)

REM Verificar gd
php -m | findstr "^gd$" >nul
if %errorlevel% equ 0 (
    echo ✓ gd - Manipulacao de imagens (OK)
) else (
    echo ✗ gd - Manipulacao de imagens (FALTANDO)
    set EXTENSIONS_OK=0
)

REM Verificar fileinfo
php -m | findstr "^fileinfo$" >nul
if %errorlevel% equ 0 (
    echo ✓ fileinfo - Deteccao de tipos de arquivo (OK)
) else (
    echo ✗ fileinfo - Deteccao de tipos de arquivo (FALTANDO)
    set EXTENSIONS_OK=0
)

REM Verificar pdo_pgsql
php -m | findstr "^pdo_pgsql$" >nul
if %errorlevel% equ 0 (
    echo ✓ pdo_pgsql - Driver PostgreSQL para PDO (OK)
) else (
    echo ✗ pdo_pgsql - Driver PostgreSQL para PDO (FALTANDO)
    set EXTENSIONS_OK=0
)

echo.
echo ========================================

if %EXTENSIONS_OK% equ 1 (
    echo ✓ TODAS AS EXTENSOES ESTAO INSTALADAS!
    echo O projeto Nexus deve funcionar corretamente.
) else (
    echo ✗ ALGUMAS EXTENSOES ESTAO FALTANDO
    echo.
    echo COMO INSTALAR:
    echo.
    echo 1. Abra o arquivo php.ini em um editor de texto:
    echo    %INI_FILE%
    echo.
    echo 2. Procure pelas linhas das extensoes e remova o ; do inicio:
    echo    ;extension=intl          →  extension=intl
    echo    ;extension=soap          →  extension=soap
    echo    ;extension=zip           →  extension=zip
    echo    ;extension=gd            →  extension=gd
    echo    ;extension=fileinfo      →  extension=fileinfo
    echo    ;extension=pdo_pgsql     →  extension=pdo_pgsql
    echo.
    echo 3. Salve o arquivo
    echo 4. Reinicie o servidor web (Apache/Nginx)
    echo.
    echo ALTERNATIVAS:
    echo • XAMPP: https://www.apachefriends.org/
    echo • WAMP: https://www.wampserver.com/
    echo • Laragon: https://laragon.org/
    echo.
    echo Deseja abrir o arquivo php.ini agora? (S/N)
    set /p OPEN_INI=
    if /i "%OPEN_INI%"=="S" (
        start notepad "%INI_FILE%"
    )
)

echo.
echo Para mais detalhes, consulte o arquivo INSTALACAO_EXTENSOES_PHP.md
echo.
pause




