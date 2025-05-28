@section('plugins.Datatables', true)
@section('plugins.Summernote', true)
@section('plugins.BootstrapSwitch', true)
@php $config = ['format' => 'DD/MM/YYYY']; @endphp
@include('person.navbar')
<div class="card-body">
    <div class="tab-content">
        <div class="tab-pane active" id="tab_data" role="tabpanel">
            @include('person.view.data')
        </div>
        <div class="tab-pane" id="tab_legal" role="tabpanel">
            @include('person.view.legal')
        </div>
        <div class="tab-pane" id="tab_address" role="tabpanel">
            @include('person.view.address')
        </div>
        <div class="tab-pane" id="tab_contact" role="tabpanel">
            @include('person.view.contact')
        </div>
        <div class="tab-pane" id="tab_social" role="tabpanel">
            <div class="row">
                <div class="col-md-6">@include('person.view.emails')</div>
                <div class="col-md-6">@include('person.view.social')</div>
            </div>
        </div>
        <div class="tab-pane" id="tab_photo" role="tabpanel">
            @include('person.view.image')
        </div>
        <div class="tab-pane" id="tab_vcard" role="tabpanel">
            @include('person.view.vcard')
        </div>
        <div class="tab-pane" id="tab_companies" role="tabpanel">
            @include('person.view.companies')
        </div>
        <div class="tab-pane" id="tab_vehicles" role="tabpanel">
            @include('person.view.vehicles')
        </div>
        <div class="tab-pane" id="tab_vinculo_orcrims" role="tabpanel">
            @include('person.view.vinculo-orcrims')
        </div>
        <div class="tab-pane" id="tab_pcpas" role="tabpanel">
            @include('person.view.pcpas')
        </div>
        <div class="tab-pane" id="tab_tjs" role="tabpanel">
            @include('person.view.tjs')
        </div>
        <div class="tab-pane" id="tab_armas" role="tabpanel">
            @include('person.view.armas')
        </div>
        <div class="tab-pane" id="tab_rais" role="tabpanel">
            @include('person.view.rais')
        </div>
        <div class="tab-pane" id="tab_bancarios" role="tabpanel">
            @include('person.view.bancarios')
        </div>
        <div class="tab-pane" id="tab_docs" role="tabpanel">
            @include('person.view.docs')
        </div>
    </div>
</div>
