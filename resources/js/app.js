/** ### MODAL PARA EXCLUSÃO ### */
$('.delete-alert').click(event => {
    event.preventDefault();
    Swal.fire({
        title: "Deseja realmente excluir?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Confirmar',
        cancelButtonText: "Cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    }).then(question => {
        if (question.isConfirmed) {
            let form = event.target.closest('form');
            form.submit();
        }
    });
});

/** ### MASCARAS DE CAMPOS ### */
$(document).ready(function () {

    $('.mask-phone').mask('99999-9999999');
    $('.mask-number-phone').mask('999999999');
    $('.mask-ddd').mask('99');
    $('.mask-date').mask('99/99/9999');
    $('.mask-code').mask('99999999');
    $('.mask-cpf').mask('999.999.999-99');
    $('.mask-cpf-number').mask('99999999999');
    $('.mask-year').mask('9999');
    

    // Máscara de dinheiro
    $('.mask-money').each(function() {
        const input = this;
        
        // Função para formatar o valor
        function formatMoney(value) {
            // Remove tudo exceto números
            value = value.replace(/\D/g, '');
            
            if (value === '') return '';
            
            // Converte para centavos
            value = parseInt(value);
            
            // Converte para formato decimal
            value = (value / 100).toFixed(2);
            
            // Adiciona pontos como separadores de milhares e vírgula como separador decimal
            value = value.replace('.', ',');
            value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            
            return 'R$ ' + value;
        }
        
        $(input).on('input keyup', function(e) {
            const cursorPosition = e.target.selectionStart;
            const oldLength = e.target.value.length;
            
            e.target.value = formatMoney(e.target.value);
            
            // Ajusta a posição do cursor
            const newLength = e.target.value.length;
            const lengthDiff = newLength - oldLength;
            const newCursorPosition = cursorPosition + lengthDiff;
            
            setTimeout(() => {
                e.target.setSelectionRange(newCursorPosition, newCursorPosition);
            }, 0);
        });
        
        // Formatar valor inicial se existir
        if ($(input).val()) {
            $(input).val(formatMoney($(input).val()));
        }
    });
    

    // Máscara de placa que normaliza o valor
    $('.mask-plate').each(function() {
        const input = this;
        let rawValue = '';
        
        $(input).on('input', function(e) {
            let value = e.target.value.replace(/[^A-Za-z0-9]/g, '').toUpperCase();
            rawValue = value;
            
            if (value.length <= 7) {
                if (value.length >= 4) {
                    const formatted = value.replace(/([A-Z]{3})([A-Z0-9]{1,4})/, '$1-$2');
                    e.target.value = formatted;
                } else {
                    e.target.value = value;
                }
            }
        });
        
        $(input).on('blur', function(e) {
            e.target.value = rawValue; // Valor sem hífen para envio
        });
        
        $(input).on('focus', function(e) {
            if (rawValue && rawValue.length >= 4) {
                const formatted = rawValue.replace(/([A-Z]{3})([A-Z0-9]{1,4})/, '$1-$2');
                e.target.value = formatted;
            }
        });
    });
});
