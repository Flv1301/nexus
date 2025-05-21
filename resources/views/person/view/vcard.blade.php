@if($person->active_orcrim && !auth()->user()->can('sisfac'))
    @include('sisfac_block')
@else
    <div class="card">
        <div class="card-body">
            @php
                $heads = [
                    'Contato',
                    'Números'
                ];
                $config = [
                    'order' => [[0, 'asc']],
                    'columns' => [null, null]
                ];
            @endphp
            <x-adminlte-datatable id="vcards" :heads="$heads" :config="$config" striped hoverable>
                @foreach($person->vcards as $vcard)
                    <tr>
                        <td>{{$vcard->fullname}}</td>
                        <td><span class="text-md">{{$vcard->phones->implode('number', ' | ')}}</span></td>
                    </tr>
                @endforeach
            </x-adminlte-datatable>
        </div>
    </div>
@endif
