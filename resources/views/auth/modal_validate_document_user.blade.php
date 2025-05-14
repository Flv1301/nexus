@section('plugins.Summernote', true)
@section('plugins.BootstrapSwitch', true)
<div class="modal fade modal-md" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Validação de Documento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="confirmationForm" action="{{ route('user.access.document.validate', $document->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <x-adminlte-input-switch label="Aceito" name="agree" data-on-color="success" data-off-color="danger"
                                             data-on-text="SIM" data-off-text="NÃO"/>
                    <x-adminlte-text-editor name="observation" label="Observação"/>
                </div>
                <div class="modal-footer">
                    <x-adminlte-button type="submit" theme="success" icon="fas fa-save" label="Confirmar" />
                </div>
            </form>
        </div>
    </div>
</div>
