{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 05/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="tab-pane active" id="tab_data" role="tabpanel">
    @include('person.data')
</div>
@if(!$person->active_orcrim || auth()->user()->can('sisfac'))
<div class="tab-pane" id="tab_legal" role="tabpanel">
    @include('person.legal')
</div>
<div class="tab-pane" id="tab_address" role="tabpanel">
    @include('person.address')
</div>
<div class="tab-pane" id="tab_contact" role="tabpanel">
    @include('person.contact')
</div>
<div class="tab-pane" id="tab_social" role="tabpanel">
    <div class="d-flex col-md-12">
        <div class="col-md-6">@include('person.emails')</div>
        <div class="col-md-6">@include('person.social')</div>
    </div>
</div>
<div class="tab-pane" id="tab_photo" role="tabpanel">
    @include('person.image')
</div>
<div class="tab-pane" id="tab_vcard" role="tabpanel">
    @include('person.vcard')
</div>
@endif
