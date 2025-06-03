#!/bin/bash

# Script para corrigir erro 413 Request Entity Too Large
# Uso: bash fix-413-error.sh

echo "🚨 Iniciando correção do erro 413..."

# Verificar se é root/sudo
if [ "$EUID" -ne 0 ]; then
    echo "❌ Este script precisa ser executado como root/sudo"
    echo "Use: sudo bash fix-413-error.sh"
    exit 1
fi

# 1. Backup e configurar PHP-FPM
echo "📋 Configurando PHP-FPM..."
if [ -f "/etc/php/8.1/fpm/php.ini" ]; then
    cp /etc/php/8.1/fpm/php.ini /etc/php/8.1/fpm/php.ini.backup.$(date +%Y%m%d_%H%M%S)
    
    sed -i 's/post_max_size = 8M/post_max_size = 100M/' /etc/php/8.1/fpm/php.ini
    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 50M/' /etc/php/8.1/fpm/php.ini
    sed -i 's/max_input_vars = 1000/max_input_vars = 5000/' /etc/php/8.1/fpm/php.ini
    sed -i 's/;max_input_vars = 5000/max_input_vars = 5000/' /etc/php/8.1/fpm/php.ini
    
    systemctl restart php8.1-fpm
    echo "✅ PHP-FPM configurado e reiniciado"
else
    echo "❌ Arquivo PHP-FPM não encontrado: /etc/php/8.1/fpm/php.ini"
    exit 1
fi

# 2. Backup e configurar NGINX
echo "📋 Configurando NGINX..."
if [ -f "/etc/nginx/nginx.conf" ]; then
    cp /etc/nginx/nginx.conf /etc/nginx/nginx.conf.backup.$(date +%Y%m%d_%H%M%S)
    
    # Verificar se já existe client_max_body_size
    if ! grep -q "client_max_body_size" /etc/nginx/nginx.conf; then
        sed -i '/http {/a\\tclient_max_body_size 100M;' /etc/nginx/nginx.conf
        echo "✅ client_max_body_size adicionado"
    else
        echo "⚠️ client_max_body_size já existe, verificando valor..."
        grep "client_max_body_size" /etc/nginx/nginx.conf
    fi
    
    # Testar sintaxe
    if nginx -t; then
        systemctl reload nginx
        echo "✅ NGINX configurado e recarregado"
    else
        echo "❌ Erro na sintaxe do NGINX, revertendo backup..."
        cp /etc/nginx/nginx.conf.backup.$(date +%Y%m%d_%H%M%S) /etc/nginx/nginx.conf
        exit 1
    fi
else
    echo "❌ Arquivo NGINX não encontrado: /etc/nginx/nginx.conf"
    exit 1
fi

# 3. Verificação final
echo ""
echo "🧪 Verificação final:"
echo "📋 PHP-FPM:"
php-fpm8.1 -i | grep -E "(upload_max_filesize|post_max_size|max_input_vars)"

echo ""
echo "📋 NGINX:"
nginx -T 2>/dev/null | grep "client_max_body_size"

echo ""
echo "📋 Status dos serviços:"
systemctl is-active php8.1-fpm nginx

echo ""
echo "🎉 Correção do erro 413 concluída!"
echo "✅ Sistema agora suporta requisições até 100MB" 