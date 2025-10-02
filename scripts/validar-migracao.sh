#!/bin/bash

# =============================================================================
# SCRIPT DE VALIDAÇÃO PÓS-MIGRAÇÃO - SISTEMA NEXUS
# =============================================================================
# Este script valida se a migração foi realizada com sucesso
# =============================================================================

# Configurações
APP_PATH="/var/www/nexus"  # Ajuste para o caminho da nova aplicação
LOG_FILE="/tmp/validacao_migracao_$(date +%Y%m%d_%H%M%S).log"

# Cores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

# Contadores
TOTAL_TESTS=0
PASSED_TESTS=0
FAILED_TESTS=0

log_test() {
    echo -e "$1" | tee -a "$LOG_FILE"
}

test_result() {
    local test_name="$1"
    local result="$2"
    local details="$3"
    
    TOTAL_TESTS=$((TOTAL_TESTS + 1))
    
    if [[ "$result" == "PASS" ]]; then
        PASSED_TESTS=$((PASSED_TESTS + 1))
        log_test "${GREEN}✓ PASS${NC} - $test_name"
        [[ -n "$details" ]] && log_test "   $details"
    else
        FAILED_TESTS=$((FAILED_TESTS + 1))
        log_test "${RED}✗ FAIL${NC} - $test_name"
        [[ -n "$details" ]] && log_test "   $details"
    fi
}

echo "=========================================="
echo "VALIDAÇÃO DE MIGRAÇÃO - SISTEMA NEXUS"
echo "=========================================="
echo "Iniciando validação em: $(date)"
echo "Aplicação: $APP_PATH"
echo "Log: $LOG_FILE"
echo ""

# =============================================================================
# 1. VALIDAÇÕES DE AMBIENTE
# =============================================================================

log_test "${BLUE}[1] VALIDAÇÕES DE AMBIENTE${NC}"

# 1.1 Verificar se diretório da aplicação existe
if [[ -d "$APP_PATH" ]]; then
    test_result "Diretório da aplicação existe" "PASS" "$APP_PATH"
else
    test_result "Diretório da aplicação existe" "FAIL" "Não encontrado: $APP_PATH"
fi

# 1.2 Verificar se arquivo .env existe
if [[ -f "$APP_PATH/.env" ]]; then
    test_result "Arquivo .env existe" "PASS"
else
    test_result "Arquivo .env existe" "FAIL" "Arquivo .env não encontrado"
fi

# 1.3 Verificar se composer install foi executado
if [[ -d "$APP_PATH/vendor" ]]; then
    test_result "Dependências Composer instaladas" "PASS"
else
    test_result "Dependências Composer instaladas" "FAIL" "Execute: composer install"
fi

# 1.4 Verificar permissões de storage
if [[ -w "$APP_PATH/storage" ]]; then
    test_result "Permissões de storage" "PASS"
else
    test_result "Permissões de storage" "FAIL" "Storage não é gravável"
fi

# =============================================================================
# 2. VALIDAÇÕES DE BANCO DE DADOS
# =============================================================================

log_test ""
log_test "${BLUE}[2] VALIDAÇÕES DE BANCO DE DADOS${NC}"

cd "$APP_PATH" 2>/dev/null

# 2.1 Testar conexão com banco
if php artisan migrate:status &>/dev/null; then
    test_result "Conexão com banco de dados" "PASS"
    
    # 2.2 Verificar se há migrações pendentes
    if php artisan migrate:status | grep -q "Pending"; then
        test_result "Status das migrações" "FAIL" "Há migrações pendentes"
    else
        test_result "Status das migrações" "PASS" "Todas as migrações executadas"
    fi
    
else
    test_result "Conexão com banco de dados" "FAIL" "Erro de conexão"
fi

# 2.3 Contar registros principais
if command -v php >/dev/null 2>&1 && [[ -f "$APP_PATH/artisan" ]]; then
    
    # Contar usuários
    USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1)
    if [[ "$USER_COUNT" =~ ^[0-9]+$ ]] && [[ "$USER_COUNT" -gt 0 ]]; then
        test_result "Dados de usuários" "PASS" "$USER_COUNT usuários encontrados"
    else
        test_result "Dados de usuários" "FAIL" "Nenhum usuário encontrado"
    fi
    
    # Contar pessoas (assumindo que existe model Person)
    PERSON_COUNT=$(php artisan tinker --execute="echo App\Models\Person::count();" 2>/dev/null | tail -1)
    if [[ "$PERSON_COUNT" =~ ^[0-9]+$ ]]; then
        test_result "Dados de pessoas" "PASS" "$PERSON_COUNT pessoas encontradas"
    else
        test_result "Dados de pessoas" "FAIL" "Erro ao contar pessoas"
    fi
    
    # Contar casos
    CASE_COUNT=$(php artisan tinker --execute="echo App\Models\CaseModel::count();" 2>/dev/null | tail -1)
    if [[ "$CASE_COUNT" =~ ^[0-9]+$ ]]; then
        test_result "Dados de casos" "PASS" "$CASE_COUNT casos encontrados"
    else
        test_result "Dados de casos" "WARN" "Erro ao contar casos (model pode ter nome diferente)"
    fi
fi

# =============================================================================
# 3. VALIDAÇÕES DE ARQUIVOS
# =============================================================================

log_test ""
log_test "${BLUE}[3] VALIDAÇÕES DE ARQUIVOS${NC}"

# 3.1 Verificar diretório de casos
CASE_DIR="$APP_PATH/storage/app/case"
if [[ -d "$CASE_DIR" ]]; then
    CASE_FILES=$(find "$CASE_DIR" -type f | wc -l)
    test_result "Diretório de casos" "PASS" "$CASE_FILES arquivos encontrados"
else
    test_result "Diretório de casos" "FAIL" "Diretório não encontrado"
fi

# 3.2 Verificar diretório de imagens
IMAGES_DIR="$APP_PATH/storage/app/images"
if [[ -d "$IMAGES_DIR" ]]; then
    IMAGE_FILES=$(find "$IMAGES_DIR" -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" \) | wc -l)
    test_result "Diretório de imagens" "PASS" "$IMAGE_FILES imagens encontradas"
else
    test_result "Diretório de imagens" "FAIL" "Diretório não encontrado"
fi

# 3.3 Verificar link simbólico do storage
if [[ -L "$APP_PATH/public/storage" ]]; then
    test_result "Link simbólico do storage" "PASS"
else
    test_result "Link simbólico do storage" "FAIL" "Execute: php artisan storage:link"
fi

# 3.4 Verificar documentos públicos
DOCS_DIR="$APP_PATH/storage/app/public/docs"
if [[ -d "$DOCS_DIR" ]]; then
    DOC_FILES=$(find "$DOCS_DIR" -type f | wc -l)
    test_result "Documentos públicos" "PASS" "$DOC_FILES documentos encontrados"
else
    test_result "Documentos públicos" "WARN" "Diretório não encontrado (pode ser normal)"
fi

# =============================================================================
# 4. VALIDAÇÕES DE CONFIGURAÇÃO
# =============================================================================

log_test ""
log_test "${BLUE}[4] VALIDAÇÕES DE CONFIGURAÇÃO${NC}"

if [[ -f "$APP_PATH/.env" ]]; then
    
    # 4.1 Verificar se APP_KEY está configurada
    if grep -q "APP_KEY=base64:" "$APP_PATH/.env"; then
        test_result "Chave da aplicação (APP_KEY)" "PASS"
    else
        test_result "Chave da aplicação (APP_KEY)" "FAIL" "Execute: php artisan key:generate"
    fi
    
    # 4.2 Verificar configuração do banco
    if grep -q "DB_CONNECTION=pgsql" "$APP_PATH/.env"; then
        test_result "Configuração do banco" "PASS" "PostgreSQL configurado"
    else
        test_result "Configuração do banco" "WARN" "Verificar configuração do banco"
    fi
    
    # 4.3 Verificar se APP_ENV está configurado
    APP_ENV=$(grep "APP_ENV=" "$APP_PATH/.env" | cut -d'=' -f2)
    if [[ -n "$APP_ENV" ]]; then
        test_result "Ambiente da aplicação" "PASS" "Ambiente: $APP_ENV"
    else
        test_result "Ambiente da aplicação" "FAIL" "APP_ENV não configurado"
    fi
fi

# =============================================================================
# 5. VALIDAÇÕES FUNCIONAIS
# =============================================================================

log_test ""
log_test "${BLUE}[5] VALIDAÇÕES FUNCIONAIS${NC}"

# 5.1 Testar artisan
if php artisan --version >/dev/null 2>&1; then
    LARAVEL_VERSION=$(php artisan --version)
    test_result "Comando artisan" "PASS" "$LARAVEL_VERSION"
else
    test_result "Comando artisan" "FAIL" "Erro ao executar artisan"
fi

# 5.2 Verificar cache
if php artisan config:clear >/dev/null 2>&1; then
    test_result "Limpeza de cache" "PASS"
else
    test_result "Limpeza de cache" "FAIL" "Erro ao limpar cache"
fi

# 5.3 Verificar se servidor web pode acessar
if [[ -f "$APP_PATH/public/index.php" ]]; then
    test_result "Arquivo index.php público" "PASS"
else
    test_result "Arquivo index.php público" "FAIL" "Arquivo não encontrado"
fi

# =============================================================================
# 6. TESTES DE INTEGRIDADE
# =============================================================================

log_test ""
log_test "${BLUE}[6] TESTES DE INTEGRIDADE${NC}"

# 6.1 Verificar tamanho dos diretórios principais
STORAGE_SIZE=$(du -sh "$APP_PATH/storage" 2>/dev/null | cut -f1)
test_result "Tamanho do storage" "INFO" "$STORAGE_SIZE"

PUBLIC_SIZE=$(du -sh "$APP_PATH/public" 2>/dev/null | cut -f1)
test_result "Tamanho do public" "INFO" "$PUBLIC_SIZE"

# 6.2 Verificar logs
if [[ -f "$APP_PATH/storage/logs/laravel.log" ]]; then
    LOG_SIZE=$(du -sh "$APP_PATH/storage/logs/laravel.log" 2>/dev/null | cut -f1)
    test_result "Arquivo de log" "PASS" "Tamanho: $LOG_SIZE"
    
    # Verificar erros recentes
    RECENT_ERRORS=$(tail -n 100 "$APP_PATH/storage/logs/laravel.log" | grep -i "ERROR" | wc -l)
    if [[ "$RECENT_ERRORS" -eq 0 ]]; then
        test_result "Erros recentes no log" "PASS" "Nenhum erro recente"
    else
        test_result "Erros recentes no log" "WARN" "$RECENT_ERRORS erros encontrados"
    fi
else
    test_result "Arquivo de log" "WARN" "Arquivo de log não encontrado"
fi

# =============================================================================
# RELATÓRIO FINAL
# =============================================================================

log_test ""
log_test "=========================================="
log_test "RELATÓRIO FINAL DA VALIDAÇÃO"
log_test "=========================================="
log_test "Total de testes: $TOTAL_TESTS"
log_test "Testes aprovados: $PASSED_TESTS"
log_test "Testes falharam: $FAILED_TESTS"

PERCENTAGE=$((PASSED_TESTS * 100 / TOTAL_TESTS))
log_test "Taxa de sucesso: $PERCENTAGE%"

if [[ "$FAILED_TESTS" -eq 0 ]]; then
    log_test ""
    log_test "${GREEN}🎉 MIGRAÇÃO VALIDADA COM SUCESSO!${NC}"
    log_test "Todos os testes passaram. O sistema está pronto para uso."
elif [[ "$PERCENTAGE" -ge 80 ]]; then
    log_test ""
    log_test "${YELLOW}⚠️  MIGRAÇÃO PARCIALMENTE VALIDADA${NC}"
    log_test "A maioria dos testes passou, mas alguns itens precisam de atenção."
    log_test "Revise os itens que falharam antes de colocar em produção."
else
    log_test ""
    log_test "${RED}❌ MIGRAÇÃO COM PROBLEMAS${NC}"
    log_test "Muitos testes falharam. Revise a migração antes de continuar."
fi

log_test ""
log_test "Log completo salvo em: $LOG_FILE"
log_test "Validação concluída em: $(date)"

# Retornar código de saída baseado nos resultados
if [[ "$FAILED_TESTS" -eq 0 ]]; then
    exit 0
elif [[ "$PERCENTAGE" -ge 80 ]]; then
    exit 1
else
    exit 2
fi


