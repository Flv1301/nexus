{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card">
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="tj_processo"
                    id="tj_processo"
                    label="Processo"
                    placeholder="Número do processo"
                />
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-select
                    name="tj_comarca"
                    id="tj_comarca"
                    label="Instância"
                    placeholder="Instância">
                    <option value="">Selecione</option>
                    <option value="PRIMEIRO GRAU">PRIMEIRO GRAU</option>
                    <option value="SEGUNDO GRAU">SEGUNDO GRAU</option>
                </x-adminlte-select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_classe"
                    id="tj_classe"
                    label="Classe"
                    placeholder="Classe"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_natureza"
                    id="tj_natureza"
                    label="Assunto"
                    placeholder="Assunto"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_autor"
                    id="tj_autor"
                    label="Autor"
                    placeholder="Autor"
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
                    placeholder="Recebido em"
                    label="Recebido em">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
        </div>
        <div class="form-row">
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
        </div>
        
        <!-- Novos campos adicionados -->
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_jurisdicao"
                    id="tj_jurisdicao"
                    label="Jurisdição"
                    placeholder="Jurisdição"
                    style="text-transform:uppercase;"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_processo_prevento"
                    id="tj_processo_prevento"
                    label="Processo Prevento"
                    placeholder="Processo Prevento"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_situacao_processo"
                    id="tj_situacao_processo"
                    label="Situação"
                    placeholder="Situação do Processo"
                    style="text-transform:uppercase;"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_distribuicao"
                    id="tj_distribuicao"
                    label="Distribuição"
                    placeholder="Distribuição"
                    style="text-transform:uppercase;"
                />
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="tj_orgao_julgador"
                    id="tj_orgao_julgador"
                    label="Órgão Julgador"
                    placeholder="Órgão Julgador"
                    style="text-transform:uppercase;"
                />
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="tj_orgao_julgador_colegiado"
                    id="tj_orgao_julgador_colegiado"
                    label="Órgão Julgador Colegiado"
                    placeholder="Órgão Julgador Colegiado"
                    style="text-transform:uppercase;"
                />
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="tj_competencia"
                    id="tj_competencia"
                    label="Competência"
                    placeholder="Competência"
                    style="text-transform:uppercase;"
                />
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_numero_inquerito_policial"
                    id="tj_numero_inquerito_policial"
                    label="Número do Inquérito Policial"
                    placeholder="Número do Inquérito Policial"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_valor_causa"
                    id="tj_valor_causa"
                    label="Valor da Causa"
                    placeholder="0,00"
                    type="number"
                    step="0.01"
                    min="0"
                />
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="tj_advogado"
                    id="tj_advogado"
                    label="Advogado"
                    placeholder="Nome do Advogado"
                    style="text-transform:uppercase;"
                />
            </div>
            <div class="form-group col-md-3">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-select name="tj_prioridade" id="tj_prioridade" label="Prioridade">
                            <option value="">Selecione...</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </x-adminlte-select>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-select name="tj_gratuidade" id="tj_gratuidade" label="Gratuidade">
                            <option value="">Selecione...</option>
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </x-adminlte-select>
                    </div>
                </div>
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



@include('person.tjsList') 