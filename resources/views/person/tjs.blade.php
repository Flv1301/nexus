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
                    name="tj_processo"
                    id="tj_processo"
                    label="Processo"
                    placeholder="Número do processo"
                />
            </div>
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