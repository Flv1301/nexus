<div class="tab-pane active" id="tab_data" role="tabpanel">
    @include('person.data')
</div>
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
<div class="tab-pane" id="tab_companies" role="tabpanel">
    @include('person.companies')
</div>
<div class="tab-pane" id="tab_vehicles" role="tabpanel">
    @include('person.vehicles')
</div>
<div class="tab-pane" id="tab_vinculo_orcrims" role="tabpanel">
    @include('person.vinculo-orcrims')
</div>
<div class="tab-pane" id="tab_pcpas" role="tabpanel">
    @include('person.pcpas')
</div>
<div class="tab-pane" id="tab_tjs" role="tabpanel">
    @include('person.tjs')
</div>
<div class="tab-pane" id="tab_armas" role="tabpanel">
    @include('person.armas')
</div>
<div class="tab-pane" id="tab_rais" role="tabpanel">
    @include('person.rais')
</div>
<div class="tab-pane" id="tab_bancarios" role="tabpanel">
    @include('person.bancarios')
</div>
<div class="tab-pane" id="tab_docs" role="tabpanel">
    @include('person.docs')
</div> 