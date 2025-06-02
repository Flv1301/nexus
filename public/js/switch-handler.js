/**
 * Script global para gerenciamento de formulários
 * Os switches foram substituídos por selects para maior confiabilidade
 */
$(document).ready(function() {
    console.log('FormHandler: Carregado com sucesso!');
    
    // Inicializar qualquer Bootstrap Switch restante (apenas para views de visualização)
    function initializeRemainingComponents() {
        $("input[data-bootstrap-switch]").each(function(){
            if (!$(this).data('bootstrap-switch-initialized')) {
                try {
                    $(this).bootstrapSwitch();
                    $(this).data('bootstrap-switch-initialized', true);
                    console.log('FormHandler: Component inicializado:', $(this).attr('name'));
                } catch (e) {
                    console.log('FormHandler: Erro ao inicializar component:', e);
                }
            }
        });
    }
    
    // Inicializar componentes na carga da página
    setTimeout(function() {
        initializeRemainingComponents();
    }, 500);
    
    // Função pública
    window.FormHandler = {
        init: initializeRemainingComponents
    };
}); 