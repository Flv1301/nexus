{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 29/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@include('sweetalert::alert')
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
