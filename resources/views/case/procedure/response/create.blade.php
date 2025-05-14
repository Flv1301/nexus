{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 24/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<form action="{{ route('procedure.response.create') }}" method="post"
      enctype="multipart/form-data">
    @csrf
    <x-adminlte-modal id="modalProcedureResponse"
                      title="Tramitação"
                      size='lg'
                      theme="dark">
        <div class="form-row">
            <div class="form-group col-md-12">
                @php
                    $config = [
                        "height" => "100",
                        "toolbar" => [
                            ['style', ['bold', 'italic', 'underline', 'clear']],
                            ['font', ['strikethrough', 'superscript', 'subscript']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'codeview', 'help']],
                        ],
                    ]
                @endphp
                <x-adminlte-text-editor name="response" label="Resposta"
                                        label-class="text-dark"
                                        placeholder="Digite seu texto de resposta."
                                        :config="$config">
                </x-adminlte-text-editor>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-adminlte-select name="status" label="Status">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </x-slot>
                    <option value="ANALISE" selected>ANÁLISE</option>
                    <option value="AGUARDANDO">AGUARDANDO</option>
                    <option value="CONCLUIDO">CONCLUÍDO</option>
                </x-adminlte-select>
            </div>
        </div>
        <x-case.uploadfile/>
        <x-slot name="footerSlot">
            <x-adminlte-button
                type="submit"
                icon="fas fa-sm fa-paper-plane"
                class="mr-auto"
                theme="success"
                label="Enviar"
            />
            <x-adminlte-button theme="danger" label="Cancelar" data-dismiss="modal"/>
        </x-slot>
    </x-adminlte-modal>
    <input type="hidden" name="case_procedure_id" id="case_procedure_id"/>
</form>
@push('js')
    <script>
        function modalProcedureResponse(e) {
            let id = e.dataset.id;
            let input = document.querySelector('#case_procedure_id');
            input.setAttribute('value', id);
        }
    </script>
@endpush
