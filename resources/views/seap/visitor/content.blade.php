{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card">
    <div class="card-header p-2">
        @include('seap.visitor.navbar')
    </div>
    <div class="card-body">
        <div class="tab-content">
            <div class="active tab-pane" id="data">
                @include('seap.visitor.profile')
            </div>
            <div class=" tab-pane" id="visitors">
                @include('seap.visitor.moviments')
            </div>
            <div class="tab-pane align-items-center w-80" id="fotos">
                @include('seap.visitor.images')
            </div>
        </div>
    </div>
</div>
