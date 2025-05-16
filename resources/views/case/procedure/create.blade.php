<form action="{{ route('procedure.create', $case) }}" method="post" enctype="multipart/form-data">
    @csrf
    <x-adminlte-modal id="modalCaseProcedure"
                      title="Tramitação"
                      size='lg'
                      theme="dark">
        <div class="overlay d-none" id="loading-page">
            <i class="fas fa-2x fa-spin fa-spinner text-gray"></i>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <x-adminlte-select2
                            name="unity_id"
                            id="unity_procedure_id"
                            label="Unidade"
                            data-placeholder="Selecione uma opção."
                            onchange="selectSector()">
                            <option/>
                            @foreach($unitys as $unity)
                                <option
                                    value="{{$unity->id}}"
                                    @if(old('unity_id') == $unity->id) selected @endif >
                                    {{$unity->name}}
                                </option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                    <div class="form-group col-md-4">
                        <x-adminlte-select2
                            name="sector_id"
                            id="sector"
                            label="Setor"
                            data-placeholder="Selecione uma opção."
                            onchange="selectUser()">
                            <option/>
                        </x-adminlte-select2>
                    </div>
                    <div class="form-group col-md-4">
                        <x-adminlte-select2
                            name="user_id"
                            id="user"
                            label="Analista"
                            data-placeholder="Selecione uma opção.">
                            <option/>
                        </x-adminlte-select2>
                    </div>
                </div>
                <div class="form-row">
                    @php $config = ['format' => 'DD/MM/YYYY']; @endphp
                    <div class="form-group col-md-4">
                        <x-adminlte-input-date name="limit_date"
                                               :config="$config"
                                               placeholder="Data limite"
                                               label="Data limite">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-gradient-warning">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input-date>
                    </div>
                    <div class="form-group col-md-8">
                        @php
                            $config = [
                                "title" => "Selecione os documentos",
                                "liveSearch" => true,
                                "liveSearchPlaceholder" => "Buscar",
                                "showTick" => true,
                                "actionsBox" => true,
                            ];
                        @endphp
                        <x-adminlte-select-bs id="files_procedure"
                                              name="files_procedure[]"
                                              label="Documento(s)"
                                              label-class="text-dark"
                                              igroup-size="md"
                                              :config="$config"
                                              multiple>
                            @foreach($files as $key => $file)
                                <option value="{{$key}}">
                                    {{$file->name . ' ( ' . $file->created_at->format('d/m/Y'). ' )'}}
                                </option>
                            @endforeach
                        </x-adminlte-select-bs>
                    </div>
                </div>
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
                        <x-adminlte-text-editor name="request" label="Solicitação"
                                                label-class="text-dark"
                                                placeholder="Solicitação."
                                                :config="$config">
                        </x-adminlte-text-editor>
                    </div>
                </div>
            </div>
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button
                type="submit"
                icon="fas fa-sm fa-save"
                class="mr-auto"
                theme="success"
                label="Enviar"
            />
            <x-adminlte-button theme="danger" label="Cancelar" data-dismiss="modal" icon="fas fa-ban"/>
        </x-slot>
    </x-adminlte-modal>
</form>
@push('js')
    <script>
        async function selectSector() {
            const loading = document.getElementById('loading-page');
            loading.classList.remove('d-none');
            const select = document.getElementById('unity_procedure_id');
            const select_opt = select.options[select.selectedIndex].value;
            const sectorElement = document.getElementById('sector');
            sectorElement.innerHTML = '';
            try {
                const response = await fetch(`{{ route('unity.sectors') }}?id=${select_opt}`);
                if (!response.ok) {
                    throw new Error('Falha ao obter os setores');
                }
                const data = await response.json();
                if (data.sectors.length > 0) {
                    const blankOption = document.createElement('option');
                    sectorElement.appendChild(blankOption);
                    for (let sector of data.sectors) {
                        const option = document.createElement('option');
                        option.value = sector.id;
                        option.textContent = sector.name;
                        sectorElement.appendChild(option);
                    }
                }
            } catch (error) {
                console.error('Erro ao obter os setores:', error);
            }
            loading.classList.add('d-none');
        }
        async function selectUser() {
            const loading = document.getElementById('loading-page');
            loading.classList.remove('d-none');
            const option = document.createElement('option');
            const userElement = document.getElementById('user');
            userElement.innerHTML = '';
            userElement.append(option);
            const select = document.getElementById('sector');
            const select_opt = select.options[select.selectedIndex].value;
            try {
                const response = await fetch(`{{ route('unity.sector.users') }}?id=${select_opt}`);
                if (!response.ok) {
                    throw new Error('Falha ao obter os usuários');
                }
                const data = await response.json();

                if (data.users.length > 0) {
                    for (let user of data.users) {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.nickname;
                        userElement.appendChild(option);
                    }
                }
            } catch (error) {
                console.error('Erro ao obter os usuários:', error);
            }
            loading.classList.add('d-none');
        }
    </script>
@endpush
