<div class="card">
    <div class="card-header">
        <span>Informações Pessoas</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="name"
                    label="Nome Completo*"
                    style="text-transform:uppercase;"
                    placeholder="Nome Completo"
                    value="{{old('name') ?? $user->name ?? ''}}"
                />
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-input
                    name="nickname"
                    label="Nome Apresentação*"
                    style="text-transform:uppercase;"
                    placeholder="Nome Apresentação"
                    value="{{old('nickname') ?? $user->nickname ?? ''}}"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="registration"
                    label="Matricula*"
                    placeholder="Matricula (Numeral)"
                    value="{{old('registration') ?? $user->registration ?? ''}}"
                />
            </div>
            <div class="form-group col-md-2">
                <x-adminlte-input
                    name="cpf"
                    label="CPF*"
                    class="mask-cpf"
                    maxlength="14"
                    placeholder="CPF"
                    value="{{old('cpf') ?? $user->cpf ?? ''}}"
                />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-adminlte-select name="office" label="Cargo*">
                    <option/>
                    <option value="Administrativo"
                        {{old('office') == 'Administrativo'
                        ? 'selected' : ($user->office == 'Administrativo'
                        ? 'selected' : '')}}
                    >
                        Administrativo
                    </option>
                    <option value="Promotor"
                        {{old('office') == 'Promotor'
                        ? 'selected' : ($user->office == 'Promotor'
                        ? 'selected' : '')}}
                    >
                        Promotor
                    </option>
                    <option value="Agente"
                        {{old('office') == 'Agente'
                        ? 'selected' : ($user->office == 'Agente'
                        ? 'selected' : '')}}>
                        Agente
                    </option>
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-4">
                @php $config = ['format' => 'DD/MM/YYYY']; @endphp
                <x-adminlte-input-date name="birth_date"
                                       class="mask-date"
                                       id="birth_date"
                                       :config="$config"
                                       placeholder="Data Nascimento"
                                       label="Data Nascimento*"
                                       value="{{old('birth_date') ?? $user->birth_date ?? ''}}">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-gradient-warning">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </div>
            <div class="col-md-1">
                <x-adminlte-input
                    name="ddd"
                    label="DDD*"
                    class="mask-ddd"
                    placeholder="DDD"
                    maxlength="2"
                    value="{{old('ddd') ?? $user->ddd ?? ''}}">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-black">
                            <i class="fas fa-phone"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
            <div class="form-group col-md-3">
                <x-adminlte-input
                    name="telephone"
                    label="Telefone*"
                    class="mask-phone"
                    maxlength="9"
                    placeholder="Telefone"
                    value="{{old('telephone') ?? $user->telephone ?? ''}}">
                    <x-slot name="prependSlot">
                        <div class="input-group-text text-black">
                            <i class="fas fa-phone"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <x-adminlte-select
                    name="unity_id"
                    id="unity_user_id"
                    label="Unidade*"
                    onchange="selectSector()">
                    <option/>
                    @foreach($unitys as $unity)
                        <option
                            value="{{$unity->id}}"
                            {{ (collect(old('unity_id'))->contains($unity->id))
                            ? 'selected' : ($unity->id == $user->unity_id
                            ? 'selected' : '') }}>
                            {{$unity->name}}
                        </option>
                    @endforeach
                </x-adminlte-select>
            </div>
            <div class="form-group col-md-4">
                <x-adminlte-select
                    name="sector_id"
                    id="sector"
                    label="Setor*" >
                    @foreach($sectors as $sector)
                        <option
                            value="{{$sector->id}}"
                            {{ (collect(old('sector_id'))->contains($sector->id))
                            ? 'selected' : ($sector->id == $user->sector_id
                            ? 'selected' : '') }}>
                            {{$sector->name}}
                        </option>
                    @endforeach
                </x-adminlte-select>
            </div>
            @can('usuario.cadastrar')
                <div class="form-group col-md-2">
                    <x-adminlte-select
                        name="coordinator"
                        id="coordinator"
                        label="Coordenador do Setor"
                    >
                        <option value="0" {{ (old('coordinator') ?? $user->coordinator ?? false) == '0' || !(old('coordinator') ?? $user->coordinator ?? false) ? 'selected' : '' }}>NÃO</option>
                        <option value="1" {{ (old('coordinator') ?? $user->coordinator ?? false) == '1' || (old('coordinator') ?? $user->coordinator ?? false) === true ? 'selected' : '' }}>SIM</option>
                    </x-adminlte-select>
                </div>
                <div class="form-group col-md-2">
                    <x-adminlte-select
                        name="status"
                        id="status"
                        label="Status"
                    >
                        <option value="0" {{ (old('status') ?? $user->status ?? false) == '0' || !(old('status') ?? $user->status ?? false) ? 'selected' : '' }}>INATIVO</option>
                        <option value="1" {{ (old('status') ?? $user->status ?? false) == '1' || (old('status') ?? $user->status ?? false) === true ? 'selected' : '' }}>ATIVO</option>
                    </x-adminlte-select>
                </div>
            @endcan
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <span>Dados de Acesso</span>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="email"
                    label="E-Mail*"
                    placeholder="E-Mail"
                    value="{{old('email') ?? $user->email ?? ''}}"
                />
            </div>
            <div class="form-group col-md-6">
                <x-adminlte-input
                    name="password"
                    type="password"
                    label="Senha*"
                    placeholder="Senha"
                    value="{{old('password')}}"
                />
            </div>
        </div>
        @can('usuario.cadastrar')
            <div class="form-row">
                <div class="form-group col-md-4">
                    <x-adminlte-select
                        name="role"
                        label="Tipo de Acesso*">
                        <option/>
                        @foreach($roles as $role)
                            <option value="{{$role->name}}"
                                {{old('role') == $role->name
                                ? 'selected' : ($user->role == $role->name
                                ? 'selected' : '')}}>
                                {{$role->name}}
                            </option>
                        @endforeach
                    </x-adminlte-select>
                </div>
                <div class="form-group col-md-8">
                    @include('permission.list')
                </div>
            </div>
        @endcan
    </div>
</div>
<div class="card">
    <div class="card-header">
        <span>Endereço</span>
    </div>
    <div class="card-body">
        @include('address.form')
    </div>
</div>
<span class="text-info">* Campos Obrigatórios</span>
@push('js')
    <script>
        async function selectSector() {
            const select = document.getElementById('unity_user_id');
            const select_opt = select.options[select.selectedIndex].value;
            const sectorElement = document.getElementById('sector');
            sectorElement.innerHTML = '';
            try {
                const response = await fetch(`{{ route('unity.sectors') }}?id=${select_opt}`);

                if (!response.ok) {
                    throw new Error('Erro ao obter os setores');
                }
                const data = await response.json();
                if (data.sectors.length > 0) {
                    data.sectors.forEach(e => {
                        sectorElement.appendChild(new Option(e.name, e.id));
                    });
                }
            } catch (error) {
                console.error('Erro ao obter os setores:', error);
            }
        }
    </script>
@endpush
