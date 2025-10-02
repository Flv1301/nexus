#!/bin/bash

# =============================================================================
# SCRIPT DE BACKUP COMPLETO PARA PRODUÇÃO AWS - SISTEMA NEXUS
# =============================================================================
# Este script faz backup completo do banco PostgreSQL e todos os arquivos
# Criado para migração de dados para outro sistema
# =============================================================================

# Configurações - AJUSTE CONFORME SEU AMBIENTE AWS
APP_NAME="nexus"
BACKUP_DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_BASE_DIR="/tmp/backup_${APP_NAME}_${BACKUP_DATE}"

# Configurações do Banco PostgreSQL (ajuste conforme seu .env de produção)
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-5432}"
DB_NAME="${DB_DATABASE}"
DB_USER="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

# Configurações AWS (ajuste conforme sua instância)
AWS_APP_PATH="/var/www/nexus"  # Ajuste para o caminho real na AWS
S3_BUCKET=""  # Opcional: bucket S3 para backup remoto

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# =============================================================================
# FUNÇÕES AUXILIARES
# =============================================================================

log_info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

log_warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

check_requirements() {
    log_info "Verificando dependências..."
    
    # Verificar se pg_dump está disponível
    if ! command -v pg_dump &> /dev/null; then
        log_error "pg_dump não encontrado. Instale postgresql-client"
        exit 1
    fi
    
    # Verificar se rsync está disponível
    if ! command -v rsync &> /dev/null; then
        log_error "rsync não encontrado. Instale rsync"
        exit 1
    fi
    
    # Verificar variáveis obrigatórias
    if [[ -z "$DB_NAME" || -z "$DB_USER" ]]; then
        log_error "Variáveis de banco de dados não configuradas"
        log_error "Configure DB_DATABASE, DB_USERNAME, DB_PASSWORD no ambiente"
        exit 1
    fi
    
    log_info "Dependências verificadas com sucesso"
}

create_backup_structure() {
    log_info "Criando estrutura de backup em: $BACKUP_BASE_DIR"
    
    mkdir -p "$BACKUP_BASE_DIR"/{database,files,storage,public,config}
    
    if [[ $? -eq 0 ]]; then
        log_info "Estrutura de backup criada com sucesso"
    else
        log_error "Erro ao criar estrutura de backup"
        exit 1
    fi
}

# =============================================================================
# BACKUP DO BANCO DE DADOS POSTGRESQL
# =============================================================================

backup_database() {
    log_info "Iniciando backup do banco de dados PostgreSQL..."
    
    export PGPASSWORD="$DB_PASSWORD"
    
    # Backup completo com dados
    local db_backup_file="$BACKUP_BASE_DIR/database/${APP_NAME}_complete_${BACKUP_DATE}.sql"
    
    pg_dump \
        --host="$DB_HOST" \
        --port="$DB_PORT" \
        --username="$DB_USER" \
        --dbname="$DB_NAME" \
        --verbose \
        --clean \
        --no-owner \
        --no-privileges \
        --format=plain \
        --file="$db_backup_file"
    
    if [[ $? -eq 0 ]]; then
        log_info "Backup do banco criado: $db_backup_file"
        
        # Compactar backup do banco
        gzip "$db_backup_file"
        log_info "Backup do banco compactado: ${db_backup_file}.gz"
    else
        log_error "Erro no backup do banco de dados"
        exit 1
    fi
    
    # Backup apenas da estrutura (sem dados)
    local schema_backup_file="$BACKUP_BASE_DIR/database/${APP_NAME}_schema_${BACKUP_DATE}.sql"
    
    pg_dump \
        --host="$DB_HOST" \
        --port="$DB_PORT" \
        --username="$DB_USER" \
        --dbname="$DB_NAME" \
        --schema-only \
        --verbose \
        --clean \
        --no-owner \
        --no-privileges \
        --format=plain \
        --file="$schema_backup_file"
    
    if [[ $? -eq 0 ]]; then
        log_info "Backup do schema criado: $schema_backup_file"
        gzip "$schema_backup_file"
    fi
    
    unset PGPASSWORD
}

# =============================================================================
# BACKUP DOS ARQUIVOS E FOTOS
# =============================================================================

backup_storage_files() {
    log_info "Iniciando backup dos arquivos do storage..."
    
    local storage_path="$AWS_APP_PATH/storage"
    local backup_storage_path="$BACKUP_BASE_DIR/storage"
    
    if [[ -d "$storage_path" ]]; then
        # Backup de todos os arquivos do storage
        rsync -av \
            --progress \
            --exclude="framework/cache/*" \
            --exclude="framework/sessions/*" \
            --exclude="framework/views/*" \
            --exclude="logs/*" \
            "$storage_path/" \
            "$backup_storage_path/"
        
        if [[ $? -eq 0 ]]; then
            log_info "Backup do storage concluído"
        else
            log_error "Erro no backup do storage"
        fi
    else
        log_warn "Diretório storage não encontrado: $storage_path"
    fi
}

backup_public_files() {
    log_info "Iniciando backup dos arquivos públicos..."
    
    local public_path="$AWS_APP_PATH/public"
    local backup_public_path="$BACKUP_BASE_DIR/public"
    
    if [[ -d "$public_path" ]]; then
        # Backup de arquivos públicos (imagens, documentos, etc.)
        rsync -av \
            --progress \
            --exclude="index.php" \
            --exclude="*.css" \
            --exclude="*.js" \
            --exclude="mix-manifest.json" \
            "$public_path/" \
            "$backup_public_path/"
        
        if [[ $? -eq 0 ]]; then
            log_info "Backup dos arquivos públicos concluído"
        else
            log_error "Erro no backup dos arquivos públicos"
        fi
    else
        log_warn "Diretório public não encontrado: $public_path"
    fi
}

backup_specific_directories() {
    log_info "Fazendo backup de diretórios específicos identificados..."
    
    # Lista de diretórios específicos com arquivos importantes
    local important_dirs=(
        "storage/app/case"
        "storage/app/images" 
        "storage/app/public/docs"
        "storage/app/public/images"
    )
    
    for dir in "${important_dirs[@]}"; do
        local source_dir="$AWS_APP_PATH/$dir"
        local backup_dir="$BACKUP_BASE_DIR/files/$(basename $dir)"
        
        if [[ -d "$source_dir" ]]; then
            log_info "Backup do diretório: $dir"
            mkdir -p "$backup_dir"
            
            rsync -av \
                --progress \
                "$source_dir/" \
                "$backup_dir/"
            
            if [[ $? -eq 0 ]]; then
                log_info "✓ Backup de $dir concluído"
            else
                log_warn "✗ Erro no backup de $dir"
            fi
        else
            log_warn "Diretório não encontrado: $source_dir"
        fi
    done
}

# =============================================================================
# BACKUP DE CONFIGURAÇÕES
# =============================================================================

backup_configurations() {
    log_info "Fazendo backup das configurações..."
    
    local config_files=(
        ".env"
        "config/database.php"
        "config/app.php"
        "config/filesystems.php"
        "composer.json"
        "composer.lock"
        "package.json"
    )
    
    for file in "${config_files[@]}"; do
        local source_file="$AWS_APP_PATH/$file"
        
        if [[ -f "$source_file" ]]; then
            cp "$source_file" "$BACKUP_BASE_DIR/config/"
            log_info "✓ Backup de $file"
        else
            log_warn "✗ Arquivo não encontrado: $file"
        fi
    done
}

# =============================================================================
# GERAÇÃO DE RELATÓRIOS E METADADOS
# =============================================================================

generate_backup_report() {
    log_info "Gerando relatório do backup..."
    
    local report_file="$BACKUP_BASE_DIR/backup_report_${BACKUP_DATE}.txt"
    
    cat > "$report_file" << EOF
=============================================================================
RELATÓRIO DE BACKUP - SISTEMA NEXUS
=============================================================================
Data do Backup: $(date)
Versão do Script: 1.0
Ambiente: Produção AWS

=============================================================================
CONFIGURAÇÕES DE BANCO DE DADOS
=============================================================================
Host: $DB_HOST
Porta: $DB_PORT
Banco: $DB_NAME
Usuário: $DB_USER

=============================================================================
ESTRUTURA DO BACKUP
=============================================================================
EOF
    
    # Adicionar estrutura de diretórios
    tree "$BACKUP_BASE_DIR" >> "$report_file" 2>/dev/null || ls -la "$BACKUP_BASE_DIR" >> "$report_file"
    
    echo "" >> "$report_file"
    echo "=============================================================================" >> "$report_file"
    echo "TAMANHOS DOS ARQUIVOS" >> "$report_file"
    echo "=============================================================================" >> "$report_file"
    
    du -sh "$BACKUP_BASE_DIR"/* >> "$report_file"
    
    echo "" >> "$report_file"
    echo "=============================================================================" >> "$report_file"
    echo "CONTAGEM DE ARQUIVOS POR TIPO" >> "$report_file"
    echo "=============================================================================" >> "$report_file"
    
    find "$BACKUP_BASE_DIR" -type f | grep -E '\.(jpg|jpeg|png|gif|pdf|doc|docx)$' | wc -l >> "$report_file"
    
    log_info "Relatório gerado: $report_file"
}

create_migration_scripts() {
    log_info "Criando scripts de migração..."
    
    # Script de restauração do banco
    cat > "$BACKUP_BASE_DIR/restore_database.sh" << 'EOF'
#!/bin/bash
# Script de restauração do banco de dados

# Configurações (ajuste conforme ambiente de destino)
NEW_DB_HOST="localhost"
NEW_DB_PORT="5432"
NEW_DB_NAME="nexus_new"
NEW_DB_USER="postgres"
NEW_DB_PASSWORD=""

# Descompactar backup
gunzip nexus_complete_*.sql.gz

# Restaurar banco
export PGPASSWORD="$NEW_DB_PASSWORD"
psql -h "$NEW_DB_HOST" -p "$NEW_DB_PORT" -U "$NEW_DB_USER" -d "$NEW_DB_NAME" -f nexus_complete_*.sql

echo "Restauração do banco concluída!"
EOF

    chmod +x "$BACKUP_BASE_DIR/restore_database.sh"
    
    # Script de migração de arquivos
    cat > "$BACKUP_BASE_DIR/migrate_files.sh" << 'EOF'
#!/bin/bash
# Script de migração de arquivos

# Configurações (ajuste conforme ambiente de destino)
NEW_APP_PATH="/var/www/novo_sistema"

# Criar estrutura de diretórios
mkdir -p "$NEW_APP_PATH"/{storage/app,public}

# Copiar arquivos
cp -r storage/* "$NEW_APP_PATH/storage/"
cp -r public/* "$NEW_APP_PATH/public/"
cp -r files/* "$NEW_APP_PATH/storage/app/"

# Ajustar permissões
chown -R www-data:www-data "$NEW_APP_PATH/storage"
chmod -R 755 "$NEW_APP_PATH/storage"

echo "Migração de arquivos concluída!"
EOF

    chmod +x "$BACKUP_BASE_DIR/migrate_files.sh"
    
    log_info "Scripts de migração criados"
}

# =============================================================================
# COMPACTAÇÃO E FINALIZAÇÃO
# =============================================================================

compress_backup() {
    log_info "Compactando backup final..."
    
    local backup_parent_dir=$(dirname "$BACKUP_BASE_DIR")
    local backup_folder_name=$(basename "$BACKUP_BASE_DIR")
    local compressed_file="${backup_parent_dir}/${backup_folder_name}.tar.gz"
    
    cd "$backup_parent_dir"
    
    tar -czf "$compressed_file" "$backup_folder_name"
    
    if [[ $? -eq 0 ]]; then
        log_info "Backup compactado criado: $compressed_file"
        
        # Mostrar tamanho final
        local size=$(du -sh "$compressed_file" | cut -f1)
        log_info "Tamanho do backup: $size"
        
        # Opcional: remover pasta descompactada
        read -p "Remover pasta descompactada? (y/n): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            rm -rf "$BACKUP_BASE_DIR"
            log_info "Pasta descompactada removida"
        fi
        
    else
        log_error "Erro ao compactar backup"
        exit 1
    fi
}

upload_to_s3() {
    if [[ -n "$S3_BUCKET" ]]; then
        log_info "Enviando backup para S3..."
        
        local compressed_file="${BACKUP_BASE_DIR}.tar.gz"
        
        if command -v aws &> /dev/null; then
            aws s3 cp "$compressed_file" "s3://$S3_BUCKET/backups/"
            
            if [[ $? -eq 0 ]]; then
                log_info "Backup enviado para S3 com sucesso"
            else
                log_warn "Erro ao enviar backup para S3"
            fi
        else
            log_warn "AWS CLI não disponível. Backup não enviado para S3"
        fi
    fi
}

# =============================================================================
# FUNÇÃO PRINCIPAL
# =============================================================================

main() {
    log_info "=== INICIANDO BACKUP COMPLETO DO SISTEMA NEXUS ==="
    log_info "Data/Hora: $(date)"
    log_info "Diretório de backup: $BACKUP_BASE_DIR"
    
    # Verificações iniciais
    check_requirements
    
    # Criar estrutura
    create_backup_structure
    
    # Executar backups
    backup_database
    backup_storage_files
    backup_public_files
    backup_specific_directories
    backup_configurations
    
    # Gerar relatórios
    generate_backup_report
    create_migration_scripts
    
    # Finalizar
    compress_backup
    upload_to_s3
    
    log_info "=== BACKUP COMPLETO FINALIZADO ==="
    log_info "Backup salvo em: ${BACKUP_BASE_DIR}.tar.gz"
}

# =============================================================================
# EXECUÇÃO
# =============================================================================

# Verificar se está sendo executado como script principal
if [[ "${BASH_SOURCE[0]}" == "${0}" ]]; then
    main "$@"
fi


