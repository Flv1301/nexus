{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 14/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="container-fluid">
    <div class="col-md-8">
        @php
            $config = [
            "title" => "Selecione o usuário...",
            "liveSearch" => true,
            "liveSearchPlaceholder" => "Pesquisa...",
            "showTick" => true,
            "actionsBox" => true,
            ];
        @endphp
        <x-adminlte-select-bs id="user" name="users[]" label="Usuários"
                              label-class="text-label" igroup-size="md" :config="$config" multiple>
            <x-slot name="prependSlot">
                <div class="input-group-text bg-gradient-warning">
                    <i class="fas fa-user"></i>
                </div>
            </x-slot>
            @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->name}} - {{$user->unity->name}} - {{$user->sector->name}}</option>
            @endforeach
        </x-adminlte-select-bs>
    </div>
    <div class="col-md-8">
        <x-adminlte-select-bs id="notification" name="type" label="Tipo de Notificação"
                              label-class="text-label" igroup-size="md" :config="$config">
            <x-slot name="prependSlot">
                <div class="input-group-text bg-gradient-warning">
                    <i class="fas fa-file"></i>
                </div>
            </x-slot>
            @foreach($types as $type)
                <option value="{{$type->value}}">{{$type->value}}</option>
            @endforeach
        </x-adminlte-select-bs>
    </div>
    <div class="col mb-3">
        <x-adminlte-input-switch name="status" label="Status" data-on-text="ATIVO" data-on-color="green" data-off-text="INATIVO" checked/>
    </div>
</div>
