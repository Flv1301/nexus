{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body">
        <div class="form-group" id="form-images">
            @php
                $config = [
                    'allowedFileTypes' => ['image'],
                    'showUpload'=> false,
                    'dropZoneEnabled'=> true,
                    'maxFileCount'=> 20,
                    'showRemove'=> true,
                    'language'=> 'pt-BR',
                    'theme' => 'explorer-fa6',
                    'fileActionSettings'=> [
                        'showRemove'=> true,
                        'showZoom'=> false,
                        'showUpload'=> false,
                        'showCaption'=> false,
                        'removeIcon'=> '',
                        'removeClass'=> 'btn btn-kv btn-default btn-outline-secondary',
                        'removeErrorClass'=> 'btn btn-kv btn-danger'
                    ],
                ];
            @endphp
            <x-adminlte-input-file-krajee id="images" name="images[]" :config="$config"
                                          igroup-size="sm" data-msg-placeholder="Escolha as imagens..."
                                          data-show-cancel="false" data-show-close="false" multiple />
        </div>
    </div>
</div>
@if($person->images->count())
    @include('person.view.image')
@endif
