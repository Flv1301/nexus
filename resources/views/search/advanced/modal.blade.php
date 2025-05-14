<div class="modal fade" id="base-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title">{{\Illuminate\Support\Str::upper($database)}}</h4>
                <div id="confidential" class="ml-auto">
                    @if($result->sigiloso)
                        <span class="text-danger text-lg text-center font-weight-bold">ESTA OCORRÊNCIA FOI MARCADA COMO SIGILOSA!</span>
                    @endif
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    @includeIf($database . '.advanced_search.view.show')
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

