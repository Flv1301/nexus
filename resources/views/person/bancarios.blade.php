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
                    name="bancario_banco"
                    id="bancario_banco"
                    label="Banco"
                    placeholder="Nome do banco"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="bancario_conta"
                    id="bancario_conta"
                    label="Conta"
                    placeholder="Número da conta"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="bancario_agencia"
                    id="bancario_agencia"
                    label="Agência"
                    placeholder="Número da agência"
                />
            </div>
            @php
                $config = ['format' => 'DD/MM/YYYY'];
            @endphp
            <div class="form-group col-md-2">
                <x-adminlte-input-date 
                    name="bancario_data_criacao"
                    id="bancario_data_criacao"
                    :config="$config"
                    placeholder="Data de criação"
                    label="Data de Criação">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input-date 
                    name="bancario_data_exclusao"
                    id="bancario_data_exclusao"
                    :config="$config"
                    placeholder="Data de exclusão"
                    label="Data de Exclusão">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <x-adminlte-button
            type="button"
            icon="fas fa-plus"
            theme="secondary"
            label="Adicionar"
            onclick="addBancario()"
        />
    </div>
</div>
@include('person.bancariosList') 