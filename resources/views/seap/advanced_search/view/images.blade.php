{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="d-flex" id="photos">
    @foreach($result->photos as $photo)
        <div class="ml-2">
            {!! $photo->presofoto_fotobin !!}
        </div>
    @endforeach
</div>

