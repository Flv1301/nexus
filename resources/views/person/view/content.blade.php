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
    </div>
</div>
