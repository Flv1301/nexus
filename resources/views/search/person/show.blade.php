{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@include('search.person.navbar')
<div class="card-body">
    <x-page-messages/>
    <div class="tab-content">
        @include('search.person.content')
    </div>
</div>
