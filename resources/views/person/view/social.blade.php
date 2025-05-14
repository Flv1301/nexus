{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 06/02/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@if($person->active_orcrim && !auth()->user()->can('sisfac'))
    @include('sisfac_block')
@else
    <div class="card">
        <div class="card-header">Redes Sociais</div>
        <div class="card-body">
            @include('contact.social.list')
        </div>
    </div>
@endif
