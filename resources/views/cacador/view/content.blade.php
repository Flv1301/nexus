@section('plugins.Datatables', true)
@section('plugins.Summernote', true)
@section('plugins.BootstrapSwitch', true)
@php $config = ['format' => 'DD/MM/YYYY']; @endphp
<div class="card-body">
    @include('cacador.view.data')
</div>


