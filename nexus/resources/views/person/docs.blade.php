{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="doc_nome_doc"
                    id="doc_nome_doc"
                    label="Nome do DOC"
                    placeholder="Nome do documento"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="doc_fonte"
                    id="doc_fonte"
                    label="Fonte"
                    placeholder="Fonte do documento"
                />
            </div>
            @php
                $config = ['format' => 'DD/MM/YYYY'];
            @endphp
            <div class="form-group col-md-3">
                <x-adminlte-input-date 
                    name="doc_data"
                    id="doc_data"
                    :config="$config"
                    placeholder="Data do documento"
                    label="Data">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3">
                <label for="doc_upload">Upload</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="doc_upload" accept=".pdf">
                        <label class="custom-file-label" for="doc_upload">Escolher arquivo PDF...</label>
                    </div>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-file-pdf"></i>
                        </span>
                    </div>
                </div>
                <small class="form-text text-muted">Apenas arquivos PDF são aceitos.</small>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <x-adminlte-button
            type="button"
            icon="fas fa-plus"
            theme="secondary"
            label="Adicionar"
            onclick="addDoc()"
        />
    </div>
</div>
@include('person.docsList') 