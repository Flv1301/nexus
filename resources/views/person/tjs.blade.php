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
                <x-adminlte-select
                    name="tj_situacao"
                    id="tj_situacao"
                    label="Situação"
                    onchange="toggleDateFields()"
                >
                    <option value="">Selecione</option>
                    <option value="Suspeito">Suspeito</option>
                    <option value="Cautelar">Cautelar</option>
                    <option value="Denunciado">Denunciado</option>
                    <option value="Condenado">Condenado</option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-3" id="data_denuncia_group" style="display: none;">
                @php $config = ['format' => 'DD/MM/YYYY']; @endphp
                <x-adminlte-input-date 
                    name="tj_data_denuncia"
                    id="tj_data_denuncia"
                    :config="$config"
                    placeholder="Data da Denúncia"
                    label="Data da Denúncia">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3" id="data_condenacao_group" style="display: none;">
                @php $config = ['format' => 'DD/MM/YYYY']; @endphp
                <x-adminlte-input-date 
                    name="tj_data_condenacao"
                    id="tj_data_condenacao"
                    :config="$config"
                    placeholder="Data da Condenação"
                    label="Data da Condenação">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_processo"
                    id="tj_processo"
                    label="Processo"
                    placeholder="Número do processo"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_natureza"
                    id="tj_natureza"
                    label="Natureza"
                    placeholder="Natureza"
                />
            </div>
            @php
                $config = ['format' => 'DD/MM/YYYY'];
            @endphp
            <div class="form-group col-md-3">
                <x-adminlte-input-date 
                    name="tj_data"
                    id="tj_data"
                    :config="$config"
                    placeholder="Data"
                    label="Data">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-select
                    name="tj_uf"
                    id="tj_uf"
                    label="UF"
                    placeholder="UF">
                    <option value="">Selecione</option>
                    @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                        <option value="{{ $uf->name }}">{{ $uf->name }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_comarca"
                    id="tj_comarca"
                    label="Comarca"
                    placeholder="Comarca"
                    style="text-transform:uppercase"
                />
            </div>
        </div>
    </div>
    <div class="card-footer">
        <x-adminlte-button
            type="button"
            icon="fas fa-plus"
            theme="secondary"
            label="Adicionar"
            onclick="addTj()"
        />
    </div>
</div>

@push('js')
<script>
function toggleDateFields() {
    const situacao = document.getElementById('tj_situacao').value;
    const dataDenunciaGroup = document.getElementById('data_denuncia_group');
    const dataCondenacaoGroup = document.getElementById('data_condenacao_group');
    
    // Esconde todos os campos de data primeiro
    dataDenunciaGroup.style.display = 'none';
    dataCondenacaoGroup.style.display = 'none';
    
    // Mostra campos baseado na situação
    if (situacao === 'Denunciado') {
        dataDenunciaGroup.style.display = 'block';
    } else if (situacao === 'Condenado') {
        dataCondenacaoGroup.style.display = 'block';
    }
}
</script>
@endpush

@include('person.tjsList') 