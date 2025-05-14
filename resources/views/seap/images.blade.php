{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div id="carouselIndicators" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        @foreach($person->peculiaritys->pluck('fotos_fotobin')->all() as $key => $photo)
            <div class="carousel-item @if($loop->first) active @endif">
                @if($photo)
                    <img src="data:image/jpeg;base64,{{ base64_encode(stream_get_contents($photo)) }}"
                         class="d-block w-100" id="{{$key}}"/>
                @endif
            </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Próximo</span>
    </a>
</div>

