{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 15/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        @include('contact.social.form')
    </div>
    <div class="card-footer">
        <x-adminlte-button
            type="button"
            icon="fas fa-plus"
            theme="secondary"
            label="Adicionar"
            onclick="addSocial()"
        />
    </div>
</div>
@include('contact.social.listPlus')
