{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 13/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@section('plugins.Select2', true)
<div class="form-group col-md-6">
    <x-adminlte-select2
        name="name"
        label="Permissão"
        placeholder="Escolha o nome da permissão.">
        @foreach($permissionsCreate as $permission)
            <option value="{{$permission}}">{{$permission}}</option>
        @endforeach
    </x-adminlte-select2>
</div>
