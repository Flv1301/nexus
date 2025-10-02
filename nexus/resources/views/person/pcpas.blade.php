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
                    name="pcpa_bo"
                    id="pcpa_bo"
                    label="BO"
                    placeholder="Número do BO"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="pcpa_natureza"
                    id="pcpa_natureza"
                    label="Natureza"
                    placeholder="Natureza"
                />
            </div>
            @php
                $config = ['format' => 'DD/MM/YYYY'];
            @endphp
            <div class="form-group col-md-3">
                <x-adminlte-input-date 
                    name="pcpa_data"
                    id="pcpa_data"
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
                    name="pcpa_uf"
                    id="pcpa_uf"
                    label="UF"
                    placeholder="UF"
                    onchange="loadCitiesByUF('pcpa_uf', 'pcpa_cidade')">
                    <option value="">Selecione</option>
                    @foreach(\App\Enums\UFBrEnum::cases() as $uf)
                        <option value="{{ $uf->name }}">{{ $uf->name }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-12">
                <x-adminlte-select
                    name="pcpa_cidade"
                    id="pcpa_cidade"
                    label="Cidade"
                    placeholder="Selecione primeiro a UF">
                    <option value="">Selecione primeiro a UF</option>
                </x-adminlte-select>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <x-adminlte-button
            type="button"
            icon="fas fa-plus"
            theme="secondary"
            label="Adicionar"
            onclick="addPcpa()"
        />
    </div>
</div>
@include('person.pcpasList') 