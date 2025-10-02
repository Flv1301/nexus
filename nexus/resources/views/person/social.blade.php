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
