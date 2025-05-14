{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 04/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@include('bnmp.navbar')
<div class="card-body">
    <div class="tab-content">
        <div class="tab-pane active" id="tab_data" role="tabpanel">
            @include('bnmp.data')
        </div>
        <div class="tab-pane" id="tab_photos" role="tabpanel">
            @include('bnmp.images')
        </div>
        <div class="tab-pane" id="tab_warrent" role="tabpanel">
            @include('bnmp.warrent')
        </div>
    </div>
</div>
