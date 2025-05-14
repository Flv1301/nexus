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
    $('.mask-phone').mask('99999-9999');
    $('.mask-number-phone').mask('999999999');
    $('.mask-ddd').mask('99');
    $('.mask-date').mask('99/99/9999');
    $('.mask-code').mask('99999999');
    $('.mask-cpf').mask('999.999.999-99');
    $('.mask-cpf-number').mask('99999999999');
    $('.mask-year').mask('9999');
});
