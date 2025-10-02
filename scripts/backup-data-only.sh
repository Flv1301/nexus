#!/bin/bash

# =============================================================================
# SCRIPT DE BACKUP SOMENTE DADOS - SISTEMA NEXUS
# =============================================================================
# Este script faz backup apenas dos dados essenciais (banco + arquivos)
# Versão simplificada para migração rápida
# =============================================================================

# Configurações
APP_NAME="nexus"
BACKUP_DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/tmp/backup_data_${APP_NAME}_${BACKUP_DATE}"

# Configurações do Banco (ajuste conforme seu .env de produção)
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-5432}"
DB_NAME="${DB_DATABASE}"
DB_USER="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

# Caminho da aplicação na AWS
AWS_APP_PATH="/var/www/nexus"

# Cores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log_info() { echo -e "${GREEN}[INFO]${NC} $1"; }
log_warn() { echo -e "${YELLOW}[WARN]${NC} $1"; }
log_error() { echo -e "${RED}[ERROR]${NC} $1"; }

# =============================================================================
# BACKUP ESSENCIAL
# =============================================================================

echo "=========================================="
echo "BACKUP DADOS ESSENCIAIS - NEXUS"
echo "=========================================="

# Criar diretório
mkdir -p "$BACKUP_DIR"/{database,files}

# 1. BACKUP DO BANCO DE DADOS
log_info "Fazendo backup do banco PostgreSQL..."
export PGPASSWORD="$DB_PASSWORD"

pg_dump \
    --host="$DB_HOST" \
    --port="$DB_PORT" \
    --username="$DB_USER" \
    --dbname="$DB_NAME" \
    --verbose \
    --clean \
    --no-owner \
    --no-privileges \
    --format=custom \
    --file="$BACKUP_DIR/database/nexus_data_${BACKUP_DATE}.dump"

if [[ $? -eq 0 ]]; then
    log_info "✓ Backup do banco concluído"
else
    log_error "✗ Erro no backup do banco"
    exit 1
fi

unset PGPASSWORD

# 2. BACKUP DOS ARQUIVOS ESSENCIAIS
log_info "Fazendo backup dos arquivos essenciais..."

# Arquivos de casos (PDFs e imagens)
if [[ -d "$AWS_APP_PATH/storage/app/case" ]]; then
    rsync -av "$AWS_APP_PATH/storage/app/case/" "$BACKUP_DIR/files/case/"
    log_info "✓ Arquivos de casos copiados"
fi

# Imagens
if [[ -d "$AWS_APP_PATH/storage/app/images" ]]; then
    rsync -av "$AWS_APP_PATH/storage/app/images/" "$BACKUP_DIR/files/images/"
    log_info "✓ Imagens copiadas"
fi

# Documentos públicos
if [[ -d "$AWS_APP_PATH/storage/app/public/docs" ]]; then
    rsync -av "$AWS_APP_PATH/storage/app/public/docs/" "$BACKUP_DIR/files/docs/"
    log_info "✓ Documentos públicos copiados"
fi

# 3. BACKUP DAS CONFIGURAÇÕES ESSENCIAIS
log_info "Copiando configurações..."
cp "$AWS_APP_PATH/.env" "$BACKUP_DIR/" 2>/dev/null || log_warn "Arquivo .env não encontrado"
cp "$AWS_APP_PATH/config/database.php" "$BACKUP_DIR/" 2>/dev/null

# 4. GERAR SCRIPT DE RESTAURAÇÃO
cat > "$BACKUP_DIR/restaurar_dados.sh" << 'EOF'
#!/bin/bash

echo "=========================================="
echo "RESTAURAÇÃO DE DADOS - NEXUS"
echo "=========================================="

# CONFIGURAÇÕES - AJUSTE PARA SEU NOVO AMBIENTE
NEW_DB_HOST="localhost"
NEW_DB_PORT="5432"
NEW_DB_NAME="nexus_novo"
NEW_DB_USER="postgres"
NEW_DB_PASSWORD="sua_senha"
NEW_APP_PATH="/var/www/novo_sistema"

echo "Restaurando banco de dados..."
export PGPASSWORD="$NEW_DB_PASSWORD"

# Restaurar banco
pg_restore \
    --host="$NEW_DB_HOST" \
    --port="$NEW_DB_PORT" \
    --username="$NEW_DB_USER" \
    --dbname="$NEW_DB_NAME" \
    --clean \
    --verbose \
    nexus_data_*.dump

if [[ $? -eq 0 ]]; then
    echo "✓ Banco restaurado com sucesso"
else
    echo "✗ Erro ao restaurar banco"
    exit 1
fi

unset PGPASSWORD

echo "Restaurando arquivos..."

# Criar estrutura de diretórios
mkdir -p "$NEW_APP_PATH/storage/app"/{case,images,public/docs}

# Copiar arquivos
cp -r files/case/* "$NEW_APP_PATH/storage/app/case/" 2>/dev/null
cp -r files/images/* "$NEW_APP_PATH/storage/app/images/" 2>/dev/null
cp -r files/docs/* "$NEW_APP_PATH/storage/app/public/docs/" 2>/dev/null

# Ajustar permissões
chown -R www-data:www-data "$NEW_APP_PATH/storage" 2>/dev/null
chmod -R 755 "$NEW_APP_PATH/storage"

echo "✓ Restauração concluída!"
echo ""
echo "PRÓXIMOS PASSOS:"
echo "1. Ajuste o arquivo .env no novo sistema"
echo "2. Execute: php artisan key:generate"
echo "3. Execute: php artisan storage:link"
echo "4. Configure as permissões do Apache/Nginx"
EOF

chmod +x "$BACKUP_DIR/restaurar_dados.sh"

# 5. COMPACTAR TUDO
log_info "Compactando backup..."
tar -czf "${BACKUP_DIR}.tar.gz" -C "$(dirname "$BACKUP_DIR")" "$(basename "$BACKUP_DIR")"

if [[ $? -eq 0 ]]; then
    BACKUP_SIZE=$(du -sh "${BACKUP_DIR}.tar.gz" | cut -f1)
    log_info "✓ Backup criado: ${BACKUP_DIR}.tar.gz ($BACKUP_SIZE)"
    
    # Opcional: remover pasta descompactada
    rm -rf "$BACKUP_DIR"
    
    echo ""
    echo "=========================================="
    echo "BACKUP CONCLUÍDO COM SUCESSO!"
    echo "=========================================="
    echo "Arquivo: ${BACKUP_DIR}.tar.gz"
    echo "Tamanho: $BACKUP_SIZE"
    echo ""
    echo "Para restaurar:"
    echo "1. Transfira o arquivo para o novo servidor"
    echo "2. Extraia: tar -xzf $(basename ${BACKUP_DIR}.tar.gz)"
    echo "3. Execute: ./restaurar_dados.sh"
    
else
    log_error "Erro ao compactar backup"
    exit 1
fi


