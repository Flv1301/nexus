# Script para verificar requisitos do Nexus no Windows
# Execute como: PowerShell -ExecutionPolicy Bypass -File check-requirements.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "    VERIFICACAO DE REQUISITOS - NEXUS" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$allGood = $true

# Verificar PHP
Write-Host "[1/6] Verificando PHP..." -ForegroundColor Yellow
try {
    $phpVersion = php --version 2>$null | Select-String "PHP (\d+\.\d+)" | ForEach-Object { $_.Matches[0].Groups[1].Value }
    if ($phpVersion) {
        $version = [version]$phpVersion
        if ($version -ge [version]"8.1") {
            Write-Host "✓ PHP $phpVersion encontrado (OK)" -ForegroundColor Green
        } else {
            Write-Host "✗ PHP $phpVersion encontrado (REQUER 8.1+)" -ForegroundColor Red
            $allGood = $false
        }
    } else {
        Write-Host "✗ PHP não encontrado" -ForegroundColor Red
        $allGood = $false
    }
} catch {
    Write-Host "✗ PHP não encontrado" -ForegroundColor Red
    $allGood = $false
}

# Verificar extensões PHP
Write-Host "[2/6] Verificando extensões PHP..." -ForegroundColor Yellow
$requiredExtensions = @(
    "pdo_pgsql", "pdo_mysql", "mbstring", "openssl", "tokenizer", 
    "xml", "ctype", "json", "bcmath", "fileinfo", "gd", "zip", 
    "simplexml", "soap"
)

$missingExtensions = @()
foreach ($ext in $requiredExtensions) {
    $result = php -m 2>$null | Select-String "^$ext$"
    if (-not $result) {
        $missingExtensions += $ext
    }
}

if ($missingExtensions.Count -eq 0) {
    Write-Host "✓ Todas as extensões PHP necessárias estão instaladas" -ForegroundColor Green
} else {
    Write-Host "✗ Extensões PHP faltando: $($missingExtensions -join ', ')" -ForegroundColor Red
    $allGood = $false
}

# Verificar Composer
Write-Host "[3/6] Verificando Composer..." -ForegroundColor Yellow
try {
    $composerVersion = composer --version 2>$null | Select-String "Composer version (\d+\.\d+)" | ForEach-Object { $_.Matches[0].Groups[1].Value }
    if ($composerVersion) {
        Write-Host "✓ Composer $composerVersion encontrado" -ForegroundColor Green
    } else {
        Write-Host "✗ Composer não encontrado" -ForegroundColor Red
        $allGood = $false
    }
} catch {
    Write-Host "✗ Composer não encontrado" -ForegroundColor Red
    $allGood = $false
}

# Verificar Node.js
Write-Host "[4/6] Verificando Node.js..." -ForegroundColor Yellow
try {
    $nodeVersion = node --version 2>$null
    if ($nodeVersion) {
        $version = [version]($nodeVersion -replace 'v', '')
        if ($version -ge [version]"16.0") {
            Write-Host "✓ Node.js $nodeVersion encontrado (OK)" -ForegroundColor Green
        } else {
            Write-Host "✗ Node.js $nodeVersion encontrado (REQUER 16.0+)" -ForegroundColor Red
            $allGood = $false
        }
    } else {
        Write-Host "✗ Node.js não encontrado" -ForegroundColor Red
        $allGood = $false
    }
} catch {
    Write-Host "✗ Node.js não encontrado" -ForegroundColor Red
    $allGood = $false
}

# Verificar NPM
Write-Host "[5/6] Verificando NPM..." -ForegroundColor Yellow
try {
    $npmVersion = npm --version 2>$null
    if ($npmVersion) {
        Write-Host "✓ NPM $npmVersion encontrado" -ForegroundColor Green
    } else {
        Write-Host "✗ NPM não encontrado" -ForegroundColor Red
        $allGood = $false
    }
} catch {
    Write-Host "✗ NPM não encontrado" -ForegroundColor Red
    $allGood = $false
}

# Verificar PostgreSQL (opcional)
Write-Host "[6/6] Verificando PostgreSQL..." -ForegroundColor Yellow
try {
    $psqlVersion = psql --version 2>$null
    if ($psqlVersion) {
        Write-Host "✓ PostgreSQL encontrado: $psqlVersion" -ForegroundColor Green
    } else {
        Write-Host "⚠ PostgreSQL não encontrado (será necessário para o banco de dados)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "⚠ PostgreSQL não encontrado (será necessário para o banco de dados)" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan

if ($allGood) {
    Write-Host "✓ TODOS OS REQUISITOS ATENDIDOS!" -ForegroundColor Green
    Write-Host "Você pode prosseguir com a instalação." -ForegroundColor Green
} else {
    Write-Host "✗ ALGUNS REQUISITOS NÃO FORAM ATENDIDOS" -ForegroundColor Red
    Write-Host "Instale os componentes faltantes antes de continuar." -ForegroundColor Red
}

Write-Host ""
Write-Host "LINKS PARA DOWNLOAD:" -ForegroundColor Cyan
Write-Host "• PHP: https://www.php.net/downloads.php" -ForegroundColor White
Write-Host "• Composer: https://getcomposer.org/download/" -ForegroundColor White
Write-Host "• Node.js: https://nodejs.org/" -ForegroundColor White
Write-Host "• PostgreSQL: https://www.postgresql.org/download/windows/" -ForegroundColor White

Write-Host ""
Write-Host "Pressione qualquer tecla para continuar..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")


