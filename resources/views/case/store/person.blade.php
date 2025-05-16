@php
    $config = [
        "title" => "Selecione os alvos",
        "liveSearch" => true,
        "liveSearchPlaceholder" => "Buscar",
        "showTick" => true,
        "actionsBox" => true,
    ];
@endphp
<div class="form-group">
    <x-adminlte-select-bs id="persons"
                          name="persons[]"
                          label="Alvos"
                          label-class="text-dark"
                          igroup-size="md"
                          :config="$config"
                          multiple
    >
        <x-slot name="prependSlot">
            <div class="input-group-text bg-gradient-gray-dark">
                <i class="fas fa-user"></i>
            </div>
        </x-slot>
        @foreach($persons as $person)
            <option value="{{ $person->id }}"
                    data-icon="fa fa-fw fa-user-secret text-info"
                {{ (collect(old('persons'))->contains($person->id))
                ? 'selected' : (in_array($person->id, $casePersons ?? []) ? 'selected' : '') }}
            >
                {{$person->name}}
            </option>
        @endforeach
    </x-adminlte-select-bs>
</div>
