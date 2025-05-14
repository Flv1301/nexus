{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="row ">
    <div class="col-md-3">
        @include('seap.profile')
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                @include('seap.navbar')
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane" id="peculiaridades">
                        @include('seap.peculiaritys')
                    </div>
                    <div class="active tab-pane" id="document">
                        @include('seap.documents')
                    </div>
                    <div class=" tab-pane" id="timeline">
                        @include('seap.moviments')
                    </div>
                    @can('seap_visitante')
                        <div class="tab-pane" id="visitors">
                            @include('seap.visitor')
                        </div>
                    @endcan
                    <div class="tab-pane align-items-center w-80" id="fotos">
                        @include('seap.images')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
