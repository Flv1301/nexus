{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/09/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-header p-2">
        @include('seap.advanced_search.view.navbar')
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane active" id="tab-profile">
                @include('seap.advanced_search.view.profile')
            </div>
            <div class="tab-pane" id="tab-documents">
                @include('seap.advanced_search.view.documents')
            </div>
            <div class="tab-pane" id="tab-peculiaritys">
                @include('seap.advanced_search.view.peculiaritys')
            </div>
            <div class=" tab-pane" id="tab-moviments">
                @include('seap.advanced_search.view.moviments')
            </div>
{{--            @can('seap_visitante')--}}
{{--                <div class="tab-pane" id="visitors">--}}
{{--                    @include('seap.visitor')--}}
{{--                </div>--}}
{{--            @endcan--}}
            <div class="tab-pane align-items-center w-80" id="tab-fotos">
                @include('seap.advanced_search.view.images')
            </div>
        </div>
    </div>
</div>

