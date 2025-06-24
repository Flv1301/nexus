class DynamicSearchFields {
    constructor() {
        this.addedFields = []; // Iniciar sem nenhum campo - todos opcionais
        this.fieldConfigurations = {
            // Aba Dados
            name: {
                name: 'name',
                placeholder: 'Nome ou Alcunha',
                label: 'Nome ou Alcunha',
                icon: 'fas fa-user'
            },
            cpf: {
                name: 'cpf',
                placeholder: 'CPF (Somente números)',
                label: 'CPF',
                class: 'mask-cpf-number',
                icon: 'fas fa-id-card'
            },
            rg: {
                name: 'rg',
                placeholder: 'RG',
                label: 'RG',
                icon: 'fas fa-id-badge'
            },
            mother: {
                name: 'mother',
                placeholder: 'Nome da Mãe',
                label: 'Genitora',
                icon: 'fas fa-female'
            },
            father: {
                name: 'father',
                placeholder: 'Nome do Pai',
                label: 'Genitor',
                icon: 'fas fa-male'
            },
            birth_date: {
                name: 'birth_date',
                placeholder: 'Data Nascimento (DD/MM/AAAA)',
                label: 'Data Nascimento',
                class: 'mask-date',
                type: 'date',
                icon: 'fas fa-calendar-alt'
            },
            birth_city: {
                name: 'birth_city',
                placeholder: 'Município de Nascimento',
                label: 'Município de Nascimento',
                icon: 'fas fa-map-marker-alt'
            },
            tattoo: {
                name: 'tattoo',
                placeholder: 'Descrição da Tatuagem',
                label: 'Tatuagem',
                icon: 'fas fa-eye'
            },
            orcrim: {
                name: 'orcrim',
                placeholder: 'Organização Criminosa',
                label: 'Orcrim',
                icon: 'fas fa-users'
            },
            area_atuacao: {
                name: 'area_atuacao',
                placeholder: 'Área de Atuação',
                label: 'Área de Atuação',
                icon: 'fas fa-map'
            },
            
            // Aba Endereços
            city: {
                name: 'city',
                placeholder: 'Cidade',
                label: 'Cidade',
                icon: 'fas fa-city'
            },
            
            // Aba Contatos
            phone: {
                name: 'phone',
                placeholder: 'Telefone',
                label: 'Telefone',
                class: 'mask-phone',
                icon: 'fas fa-phone'
            },
            
            // Aba Social
            email: {
                name: 'email',
                placeholder: 'E-mail',
                label: 'E-mail',
                icon: 'fas fa-envelope'
            },
            
            // Aba Infopen
            matricula: {
                name: 'matricula',
                placeholder: 'Matrícula',
                label: 'Matrícula',
                icon: 'fas fa-id-card-alt'
            },
            
            // Aba Veículos
            placa: {
                name: 'placa',
                placeholder: 'Placa do Veículo',
                label: 'Placa',
                class: 'mask-placa',
                icon: 'fas fa-car'
            },
            
            // Aba Antecedentes
            bo: {
                name: 'bo',
                placeholder: 'Número do BO',
                label: 'BO',
                icon: 'fas fa-file-alt'
            },
            natureza: {
                name: 'natureza',
                placeholder: 'Natureza do BO',
                label: 'Natureza',
                icon: 'fas fa-list-ul'
            },
            
            // Aba Processos
            processo: {
                name: 'processo',
                placeholder: 'Número do Processo',
                label: 'Processo',
                icon: 'fas fa-gavel'
            },
            situacao: {
                name: 'situacao',
                placeholder: 'Situação do Processo',
                label: 'Situação',
                type: 'select',
                options: [
                    { value: '', text: 'Selecione...' },
                    { value: 'Suspeito', text: 'Suspeito' },
                    { value: 'Cautelar', text: 'Cautelar' },
                    { value: 'Denunciado', text: 'Denunciado' },
                    { value: 'Condenado', text: 'Condenado' }
                ],
                icon: 'fas fa-user-shield'
            }
        };
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.updateFieldSelector();
        this.setupFormValidation();
        this.showInitialMessage();
    }

    showInitialMessage() {
        this.showToast('Adicione campos de pesquisa usando o seletor abaixo', 'info');
    }

    setupEventListeners() {
        const fieldSelector = document.getElementById('field-selector');
        if (fieldSelector) {
            fieldSelector.addEventListener('change', (e) => {
                this.handleFieldSelection(e.target.value);
                e.target.value = '';
            });
        }

        const removeAllButton = document.getElementById('remove-all-fields');
        if (removeAllButton) {
            removeAllButton.addEventListener('click', () => {
                this.removeAllFields();
            });
        }
    }

    setupFormValidation() {
        // Configurar validação do formulário
        const form = document.getElementById('form-search');
        if (form) {
            form.addEventListener('submit', (e) => {
                const errors = this.validateAllFields();
                if (errors.length > 0) {
                    e.preventDefault();
                    this.showToast(errors.join(', '), 'warning');
                    return;
                }

                // Verificar se pelo menos um campo foi preenchido
                const filledFields = this.getFilledFields();
                if (filledFields.length === 0) {
                    e.preventDefault();
                    this.showToast('Adicione pelo menos um campo de pesquisa', 'warning');
                    return;
                }
            });
        }
    }

    getFilledFields() {
        return this.addedFields.filter(fieldType => {
            const input = document.querySelector(`input[name="${fieldType}"]`);
            const select = document.querySelector(`select[name="${fieldType}"]`);
            
            if (input) {
                return input.value.trim() !== '';
            } else if (select) {
                return select.value.trim() !== '';
            }
            return false;
        });
    }

    handleFieldSelection(fieldType) {
        if (fieldType && !this.addedFields.includes(fieldType)) {
            this.addField(fieldType);
        }
    }

    addField(fieldType, value = '') {
        const config = this.fieldConfigurations[fieldType];
        if (!config) return;

        const selectedFields = document.getElementById('selected-fields');
        if (!selectedFields) return;

        const fieldContainer = this.createFieldContainer(fieldType, config, value);
        
        // Adicionar animação de entrada
        fieldContainer.style.opacity = '0';
        fieldContainer.style.transform = 'translateY(-10px)';
        selectedFields.appendChild(fieldContainer);
        
        // Animar entrada
        setTimeout(() => {
            fieldContainer.style.transition = 'all 0.3s ease-in-out';
            fieldContainer.style.opacity = '1';
            fieldContainer.style.transform = 'translateY(0)';
        }, 10);

        this.addedFields.push(fieldType);
        this.applyMasks(fieldContainer, config);
        this.updateFieldSelector();
        
        // Foca no campo recém adicionado
        const input = fieldContainer.querySelector('input');
        const select = fieldContainer.querySelector('select');
        if (input) {
            setTimeout(() => input.focus(), 350);
        } else if (select) {
            setTimeout(() => select.focus(), 350);
        }
    }

    createFieldContainer(fieldType, config, value = '') {
        const fieldContainer = document.createElement('div');
        fieldContainer.className = 'form-group field-container';
        fieldContainer.setAttribute('data-field', fieldType);

        const fieldHtml = this.generateFieldHtml(config, value);
        fieldContainer.innerHTML = fieldHtml;
        
        return fieldContainer;
    }

    generateFieldHtml(config, value) {
        const iconHtml = config.icon ? `<i class="${config.icon}"></i> ` : '';
        
        if (config.type === 'date') {
            return `
                <label for="${config.name}">${iconHtml}${config.label}</label>
                <div class="input-group">
                    <input type="text" name="${config.name}" id="${config.name}" 
                           class="form-control ${config.class || ''}" 
                           placeholder="${config.placeholder}"
                           value="${value}"
                           maxlength="10"
                           data-field-type="${config.name}">
                    <div class="input-group-append">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            `;
        } else if (config.type === 'select') {
            let optionsHtml = '';
            if (config.options) {
                config.options.forEach(option => {
                    const selected = option.value === value ? 'selected' : '';
                    optionsHtml += `<option value="${option.value}" ${selected}>${option.text}</option>`;
                });
            }
            
            return `
                <label for="${config.name}">${iconHtml}${config.label}</label>
                <select name="${config.name}" id="${config.name}" 
                        class="form-control ${config.class || ''}" 
                        data-field-type="${config.name}">
                    ${optionsHtml}
                </select>
            `;
        } else {
            let maxLength = '';
            if (config.name === 'cpf') maxLength = 'maxlength="11"';
            else if (config.name === 'phone') maxLength = 'maxlength="15"';
            else if (config.name === 'placa') maxLength = 'maxlength="8"';
            
            return `
                <label for="${config.name}">${iconHtml}${config.label}</label>
                <input type="text" name="${config.name}" id="${config.name}" 
                       class="form-control ${config.class || ''}" 
                       placeholder="${config.placeholder}"
                       value="${value}" ${maxLength}
                       data-field-type="${config.name}">
            `;
        }
    }

    removeAllFields() {
        if (this.addedFields.length === 0) {
            this.showToast('Não há campos para remover', 'info');
            return;
        }

        if (confirm(`Deseja remover ${this.addedFields.length} campo(s)?`)) {
            console.log('Removendo todos os campos:', this.addedFields);
            
            const fieldsToRemove = [...this.addedFields]; // Copia do array
            const fieldContainers = document.querySelectorAll('.field-container');
            
            // Limpar array de campos imediatamente
            this.addedFields = [];
            
            // Animar saída de todos os campos simultaneamente
            fieldContainers.forEach((container, index) => {
                container.style.transition = 'all 0.3s ease-in-out';
                container.style.opacity = '0';
                container.style.transform = 'translateY(-10px)';
                
                // Remover do DOM após animação
                setTimeout(() => {
                    if (container.parentNode) {
                        container.remove();
                    }
                }, 300);
            });
            
            // Atualizar interface após animações
            setTimeout(() => {
                this.updateFieldSelector();
                this.showToast(`${fieldsToRemove.length} campo(s) removido(s) com sucesso`, 'success');
                this.showInitialMessage();
            }, 400);
        }
    }

    updateFieldSelector() {
        const selector = document.getElementById('field-selector');
        if (!selector) return;

        const options = selector.querySelectorAll('option[value!=""]');
        
        options.forEach(option => {
            option.style.display = this.addedFields.includes(option.value) ? 'none' : 'block';
        });

        // Atualizar texto do placeholder
        const availableCount = options.length - this.addedFields.length;
        const defaultOption = selector.querySelector('option[value=""]');
        if (defaultOption) {
            if (availableCount > 0) {
                defaultOption.textContent = `Escolha um campo... (${availableCount} disponíveis)`;
                defaultOption.disabled = false;
            } else {
                defaultOption.textContent = 'Todos os campos foram adicionados';
                defaultOption.disabled = true;
            }
        }
    }

    applyMasks(fieldContainer, config) {
        const input = fieldContainer.querySelector('input');
        if (!input) return;

        switch (config.class) {
            case 'mask-cpf-number':
                this.applyCpfMask(input);
                break;
            case 'mask-date':
                this.applyDateMask(input);
                break;
            case 'mask-phone':
                this.applyPhoneMask(input);
                break;
            case 'mask-placa':
                this.applyPlacaMask(input);
                break;
        }
    }

    applyCpfMask(input) {
        // Usa a função global do sistema se estiver disponível
        if (window.applyCPFMaskToField) {
            window.applyCPFMaskToField(input);
        } else {
            // Fallback para máscara básica
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    e.target.value = value;
                }
            });
            
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
                    e.preventDefault();
                }
            });
        }
    }

    applyPhoneMask(input) {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length <= 11) {
                if (value.length >= 11) {
                    // Celular: (XX) XXXXX-XXXX
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length >= 10) {
                    // Fixo: (XX) XXXX-XXXX
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else if (value.length >= 6) {
                    value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                } else if (value.length >= 2) {
                    value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                }
                e.target.value = value;
            }
        });
    }

    applyPlacaMask(input) {
        // Armazenar o valor sem máscara para envio
        let rawValue = '';
        
        input.addEventListener('input', (e) => {
            // Remover tudo que não é letra ou número
            let value = e.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            rawValue = value; // Valor limpo para envio
            
            // Aplicar formatação visual apenas
            if (value.length <= 7) {
                if (value.length >= 4) {
                    // Formato visual: ABC-1234 ou ABC1D23 (Mercosul)
                    const formatted = value.replace(/([A-Z]{3})([A-Z0-9]{1,4})/, '$1-$2');
                    e.target.value = formatted;
                } else {
                    e.target.value = value;
                }
            }
        });
        
        // No blur, usar sempre o valor sem hífen
        input.addEventListener('blur', (e) => {
            e.target.value = rawValue;
        });
        
        // No focus, mostrar formatado para ajudar o usuário
        input.addEventListener('focus', (e) => {
            if (rawValue && rawValue.length >= 4) {
                const formatted = rawValue.replace(/([A-Z]{3})([A-Z0-9]{1,4})/, '$1-$2');
                e.target.value = formatted;
            }
        });
        
        input.addEventListener('keypress', (e) => {
            if (!/[A-Za-z0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
                e.preventDefault();
            }
        });
    }

    applyDateMask(input) {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            if (value.length >= 5) {
                value = value.substring(0, 5) + '/' + value.substring(5, 9);
            }
            e.target.value = value;
        });

        // Validação básica da data
        input.addEventListener('blur', (e) => {
            const dateValue = e.target.value;
            if (dateValue && dateValue.length === 10 && !this.isValidDate(dateValue)) {
                this.showToast('Data inválida. Use o formato DD/MM/AAAA', 'warning');
                setTimeout(() => e.target.focus(), 100);
            }
        });
    }

    isValidDate(dateString) {
        const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
        const match = dateString.match(regex);
        if (!match) return false;

        const day = parseInt(match[1], 10);
        const month = parseInt(match[2], 10);
        const year = parseInt(match[3], 10);

        if (month < 1 || month > 12) return false;
        if (day < 1 || day > 31) return false;

        const date = new Date(year, month - 1, day);
        return date.getFullYear() === year && 
               date.getMonth() === month - 1 && 
               date.getDate() === day &&
               year >= 1900 && year <= new Date().getFullYear();
    }

    showToast(message, type = 'info') {
        // Remove toasts existentes
        document.querySelectorAll('.dynamic-toast').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed dynamic-toast`;
        toast.style.cssText = `
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            min-width: 300px;
            max-width: 400px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        const typeIcons = {
            success: 'fas fa-check-circle',
            warning: 'fas fa-exclamation-triangle',
            danger: 'fas fa-times-circle',
            info: 'fas fa-info-circle'
        };
        
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="${typeIcons[type] || typeIcons.info} mr-2"></i>
                <span>${message}</span>
                <button type="button" class="close ml-auto" onclick="this.parentElement.parentElement.remove()">
                    <span>&times;</span>
                </button>
            </div>
        `;

        document.body.appendChild(toast);
        
        // Animar entrada
        setTimeout(() => toast.style.opacity = '1', 10);
        
        // Remover automaticamente após 4 segundos
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    }

    loadExistingFields(existingData) {
        Object.keys(existingData).forEach(fieldType => {
            const value = existingData[fieldType];
            if (value && this.fieldConfigurations[fieldType] && !this.addedFields.includes(fieldType)) {
                this.addField(fieldType, value);
            }
        });
    }

    getFieldsData() {
        const data = {};
        this.addedFields.forEach(fieldType => {
            const input = document.querySelector(`input[name="${fieldType}"]`);
            if (input) {
                data[fieldType] = input.value;
            }
        });
        return data;
    }

    // Método para validar todos os campos antes do envio
    validateAllFields() {
        const errors = [];
        
        this.addedFields.forEach(fieldType => {
            const input = document.querySelector(`input[name="${fieldType}"]`);
            if (input && input.value) {
                switch (fieldType) {
                    case 'cpf':
                        if (input.value.replace(/\D/g, '').length !== 11) {
                            errors.push('CPF deve conter 11 dígitos');
                        }
                        break;
                    case 'birth_date':
                        if (!this.isValidDate(input.value)) {
                            errors.push('Data de nascimento inválida');
                        }
                        break;
                    case 'email':
                        if (!this.isValidEmail(input.value)) {
                            errors.push('E-mail inválido');
                        }
                        break;
                    case 'phone':
                        const phoneDigits = input.value.replace(/\D/g, '');
                        if (phoneDigits.length < 10 || phoneDigits.length > 11) {
                            errors.push('Telefone deve ter 10 ou 11 dígitos');
                        }
                        break;
                    case 'placa':
                        if (!this.isValidPlaca(input.value)) {
                            errors.push('Placa inválida');
                        }
                        break;
                }
            }
        });
        
        return errors;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPlaca(placa) {
        const placaClean = placa.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
        
        // Placa antiga: ABC1234
        const placaAntigaRegex = /^[A-Z]{3}[0-9]{4}$/;
        
        // Placa Mercosul: ABC1D23
        const placaMercosulRegex = /^[A-Z]{3}[0-9][A-Z][0-9]{2}$/;
        
        return placaAntigaRegex.test(placaClean) || placaMercosulRegex.test(placaClean);
    }

    // Método para obter estatísticas dos campos
    getFieldsStats() {
        const total = Object.keys(this.fieldConfigurations).length;
        const added = this.addedFields.length;
        const filled = this.getFilledFields().length;
        
        return {
            total,
            added,
            available: total - added,
            filled,
            empty: added - filled
        };
    }

    // Método para debug
    debug() {
        console.log('DynamicSearchFields Debug Info:');
        console.log('Added Fields:', this.addedFields);
        console.log('Field Stats:', this.getFieldsStats());
        console.log('Current Data:', this.getFieldsData());
        console.log('Filled Fields:', this.getFilledFields());
        
        // Debug adicional para troubleshooting
        const inputs = document.querySelectorAll('#selected-fields input');
        console.log('Inputs no DOM:', inputs.length);
        inputs.forEach((input, i) => {
            console.log(`  Input ${i + 1}: ${input.name} = "${input.value}"`);
        });
    }
}

// Inicializar quando o DOM estiver carregado
let dynamicFields;
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando DynamicSearchFields...');
    dynamicFields = new DynamicSearchFields();
    
    // Torna a instância globalmente acessível
    window.dynamicFields = dynamicFields;
    
    console.log('DynamicSearchFields inicializado com sucesso!');
}); /* Cache bust Tue Jun 17 02:38:49 PM UTC 2025 */
