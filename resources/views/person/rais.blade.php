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
                    name="rais_empresa_orgao"
                    id="rais_empresa_orgao"
                    label="Empresa/Orgão"
                    placeholder="Nome da empresa ou órgão"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="rais_cnpj"
                    id="rais_cnpj"
                    label="CNPJ"
                    placeholder="CNPJ"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="rais_tipo_vinculo"
                    id="rais_tipo_vinculo"
                    label="Tipo de Vínculo"
                    placeholder="Tipo de vínculo"
                />
            </div>
            @php
                $config = ['format' => 'DD/MM/YYYY'];
            @endphp
            <div class="form-group col-md-2">
                <x-adminlte-input-date 
                    name="rais_admissao"
                    id="rais_admissao"
                    :config="$config"
                    placeholder="Data de admissão"
                    label="Admissão">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="rais_situacao"
                    id="rais_situacao"
                    label="Situação"
                    placeholder="Situação"
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
            onclick="addRais()"
        />
    </div>
</div>
@include('person.raisList') 