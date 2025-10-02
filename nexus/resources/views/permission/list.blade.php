{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 09/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@php
    $config = [
        "title" => "Selecione as permissões",
        "liveSearch" => true,
        "liveSearchPlaceholder" => "Buscar",
        "showTick" => true,
        "actionsBox" => true,
    ];
@endphp
<x-adminlte-select-bs
    name="permissions[]"
    id="permissions"
    label="Permissões"
    :config="$config"
    multiple>
    @foreach($permissions as $permission)
        <option class="text-primary"
                value="{{$permission->id}}"
            {{ (collect(old('permissions'))->contains($permission->id))
            ? 'selected' : (in_array($permission->id, $permissionsKey ?? []) ? 'selected' : '') }}>
            {{$permission->name}}
        </option>
    @endforeach
</x-adminlte-select-bs>
