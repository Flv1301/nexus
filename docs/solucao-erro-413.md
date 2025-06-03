# 🚨 Solução Erro 413 Request Entity Too Large

## 📋 **Problema Reportado**
```
413 Request Entity Too Large
```
**Situação:** Erro ao tentar cadastrar uma pessoa na produção.

## 🔍 **Diagnóstico Realizado**

### **Configurações Iniciais (Problemáticas):**
```bash
# PHP-FPM 8.1
max_input_vars = 1000        # ❌ Muito baixo
post_max_size = 8M           # ❌ Muito baixo  
upload_max_filesize = 2M     # ❌ Muito baixo
memory_limit = 128M          # ✅ OK

# NGINX
client_max_body_size = 1M    # ❌ Padrão muito baixo (não configurado)
```

## 🛠️ **Solução Implementada**

### **1. Correção PHP-FPM (`/etc/php/8.1/fpm/php.ini`)**
```bash
# Backup
sudo cp /etc/php/8.1/fpm/php.ini /etc/php/8.1/fpm/php.ini.backup

# Atualizações
sudo sed -i 's/post_max_size = 8M/post_max_size = 100M/' /etc/php/8.1/fpm/php.ini
sudo sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 50M/' /etc/php/8.1/fpm/php.ini
sudo sed -i 's/max_input_vars = 1000/max_input_vars = 5000/' /etc/php/8.1/fpm/php.ini
sudo sed -i 's/;max_input_vars = 5000/max_input_vars = 5000/' /etc/php/8.1/fpm/php.ini

# Reiniciar serviço
sudo systemctl restart php8.1-fpm
```

### **2. Correção NGINX (`/etc/nginx/nginx.conf`)**
```bash
# Backup  
sudo cp /etc/nginx/nginx.conf /etc/nginx/nginx.conf.backup

# Adicionar client_max_body_size
sudo sed -i '/http {/a\\tclient_max_body_size 100M;' /etc/nginx/nginx.conf

# Testar e recarregar
sudo nginx -t
sudo systemctl reload nginx
```

## ✅ **Resultado Final**

### **Configurações Ativas:**
```bash
# PHP-FPM 8.1
max_input_vars = 5000        # ✅ Aumentado 5x
post_max_size = 100M         # ✅ Aumentado 12.5x
upload_max_filesize = 50M    # ✅ Aumentado 25x
memory_limit = 128M          # ✅ Mantido

# NGINX  
client_max_body_size = 100M  # ✅ Aumentado 100x
```

### **Status dos Serviços:**
- ✅ **php8.1-fpm**: active
- ✅ **nginx**: active

## 🧪 **Como Testar**

### **Verificação PHP-FPM:**
```bash
php-fpm8.1 -i | grep -E "(upload_max_filesize|post_max_size|max_input_vars)"
```

### **Verificação NGINX:**
```bash
sudo nginx -T 2>/dev/null | grep "client_max_body_size"
```

### **Status dos Serviços:**
```bash
sudo systemctl is-active php8.1-fpm nginx
```

## 📄 **Arquivos de Backup Criados**
- `/etc/php/8.1/fpm/php.ini.backup`
- `/etc/nginx/nginx.conf.backup`

## 🎯 **Benefícios da Solução**

### **Antes:**
- ❌ Limite total: **1M** (NGINX)
- ❌ Formulários grandes: **FALHA**
- ❌ Upload de arquivos: **LIMITADO a 2M**

### **Depois:**  
- ✅ Limite total: **100M** (NGINX + PHP)
- ✅ Formulários grandes: **FUNCIONANDO**
- ✅ Upload de arquivos: **ATÉ 50M**
- ✅ Variáveis de entrada: **5000 campos**

## 🚀 **Sistema Pronto**

**✅ O cadastro de pessoas na produção agora deve funcionar normalmente!**

### **Capacidades Atuais:**
- 📝 **Formulários complexos** com muitos campos
- 📎 **Upload de arquivos** até 50MB
- 🔢 **Até 5000 variáveis** por requisição
- 💾 **Requisições até 100MB** de tamanho total

---

**Data da solução:** Dezembro 2024  
**Ambiente:** Ubuntu 22.04 + PHP 8.1 + NGINX  
**Status:** ✅ RESOLVIDO 