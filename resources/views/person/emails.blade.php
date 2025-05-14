{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 06/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        @include('contact.email.form')
    </div>
    <div class="card-footer">
        <x-adminlte-button
            type="button"
            icon="fas fa-plus"
            theme="secondary"
            label="Adicionar"
            onclick="addEmail()"
        />
    </div>
</div>
@include('contact.email.listPlus')
