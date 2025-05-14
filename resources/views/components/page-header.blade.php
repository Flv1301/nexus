{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex">
                <div class="mr-auto">
                    <h1 class="h1 text-light">
                        @isset($title)
                            {!! $title !!}
                        @endisset
                    </h1>
                </div>
                {{$slot}}
            </div>
        </div>
@endsection
