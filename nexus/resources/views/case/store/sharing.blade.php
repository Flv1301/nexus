<div class="form-row">
    <div class="form-group col-md-12">
        @php
            $config = [
                "title" => "Selecione os usuários que deseja que tenha acesso ao caso.",
                "liveSearch" => true,
                "liveSearchPlaceholder" => "Buscar",
                "showTick" => true,
                "actionsBox" => true,
            ];
        @endphp
        <x-adminlte-select-bs id="users_allowed"
                              name="users_allowed[]"
                              label="Usuários"
                              label-class="text-dark"
                              igroup-size="md"
                              :config="$config"
                              multiple>
            <x-slot name="prependSlot">
                <div class="input-group-text bg-gradient-gray-dark">
                    <i class="fas fa-user"></i>
                </div>
            </x-slot>
            @foreach($users as $user)
                <option value="{{ $user->id }}" data-icon="fa fa-fw fa-user-secret text-info"
                    {{ (collect(old('users_allowed'))->contains($user->id))
                    ? 'selected' : (in_array($user->id, $caseUsers ?? []) ? 'selected' : '') }}>
                    {{$user->name}}
                </option>
            @endforeach
        </x-adminlte-select-bs>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-12">
        @php
            $config = [
                "title" => "Selecione o(s) setor(es) que possa ter acesso ao caso. Todos do setor selecionado poderão ter acesso.",
                "liveSearch" => true,
                "liveSearchPlaceholder" => "Buscar",
                "showTick" => true,
                "actionsBox" => true,
            ];
        @endphp
        <x-adminlte-select-bs id="sectors_allowed"
                              name="sectors_allowed[]"
                              label="Setores"
                              label-class="text-dark"
                              igroup-size="md"
                              :config="$config"
                              multiple>
            <x-slot name="prependSlot">
                <div class="input-group-text bg-gradient-gray-dark">
                    <i class="fas fa-building"></i>
                </div>
            </x-slot>
            @foreach($sectors as $sector)
                <option value="{{ $sector->id }}"
                        data-icon="fa fa-fw fa-user-secret text-info"
                    {{ (collect(old('sectors_allowed'))->contains($sector->id))
                    ? 'selected' : (in_array($sector->id, $caseSectors ?? []) ? 'selected' : '' ) }}>
                    {{ $sector->name }} - {{$sector->unity->name}}
                </option>
            @endforeach
        </x-adminlte-select-bs>
    </div>
</div>
{{--<div class="form-row">--}}
{{--    <div class="form-group col-md-12">--}}
{{--        @php--}}
{{--            $config = [--}}
{{--                "title" => "Selecione (a)s unidade(s) que possa ter acesso ao caso. Todos da unidade selecionada poderão ter acesso.",--}}
{{--                "liveSearch" => true,--}}
{{--                "liveSearchPlaceholder" => "Buscar",--}}
{{--                "showTick" => true,--}}
{{--                "actionsBox" => true,--}}
{{--            ];--}}
{{--        @endphp--}}
{{--        <x-adminlte-select-bs id="unitys_allowed"--}}
{{--                              name="unitys_allowed[]"--}}
{{--                              label="Unidades"--}}
{{--                              label-class="text-dark"--}}
{{--                              igroup-size="md"--}}
{{--                              :config="$config"--}}
{{--                              multiple>--}}
{{--            <x-slot name="prependSlot">--}}
{{--                <div class="input-group-text bg-gradient-gray-dark">--}}
{{--                    <i class="fas fa-building"></i>--}}
{{--                </div>--}}
{{--            </x-slot>--}}
{{--            @foreach($unitys as $unity)--}}
{{--                <option value="{{ $unity->id }}"--}}
{{--                        data-icon="fa fa-fw fa-user-secret text-info"--}}
{{--                    {{ (collect(old('unitys_allowed'))->contains($unity->id))--}}
{{--                    ? 'selected' : (in_array($unity->id, $caseUnitys ?? []) ? 'selected' : '' ) }}>--}}
{{--                    {{ $unity->name }}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </x-adminlte-select-bs>--}}
{{--    </div>--}}
{{--</div>--}}
