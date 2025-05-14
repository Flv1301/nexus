{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 04/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-body" id="images-preview">
        @foreach ($person['foto'] as $count => $image)
            @if($image['foto'])
                <a href="#" label="Open Modal" data-toggle="modal" data-target="#modalMin-{{$count}}">
                    <img class="img-thumbnail"
                         src="data:{{$image['tipoConteudoFoto']}};base64,{{base64_encode(base64_decode($image['foto']))}}"
                         alt="Foto BNPM"/>
                </a>
                <x-adminlte-modal size="xl" id="modalMin-{{$count}}" title="Data da Foto {{$image['dataFoto']}}"
                                  style="text-align: center">
                    <img src="data:{{$image['tipoConteudoFoto']}};base64,{{base64_encode(base64_decode($image['foto']))}}"
                        alt="Foto BNMP"/>
                </x-adminlte-modal>
            @endif
        @endforeach
    </div>
</div>
