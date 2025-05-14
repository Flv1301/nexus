@php
    use App\Models\Gi2;
@endphp
@section('content_header')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-bar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Aparelhos Furtados</span>
                    <span class="info-box-number">{{$imeiCount}}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-chart-area"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Aparelhos Coletados GI2</span>
                    <span class="info-box-number">{{$gi2Count}}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <a href="{{route("gi2.intersection")}}">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-chart-line"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Interseção</span>
                        <span class="info-box-number">{{$intersection}}</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-crosshairs"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Alvos Localizados</span>
                    <span class="info-box-number"></span>
                </div>
            </div>
        </div>
    </div>
    <form action="{{route('gi2.search')}}" method="post">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="col-md-4 mt-3">
                        <x-adminlte-input name="imei_imsi" placeholder="Digite o IMEI ou IMSI para pesquisar"/>
                    </div>
                    <x-adminlte-button type="submit" label="Pesquisar" icon="fas fa-search" theme="secondary"/>
                    @can('gi2.importacao')
                        <a href="gi2/create" class="btn btn-primary ml-2">Importar Arquivo CSV</a>
                    @endcan

                    <div class="text-right right ml-2">
                        <a class="btn btn-success" id="btFilter"><i class="fa fa-filter" aria-hidden="true"></i> </a>
                    </div>
                    <div class="text-right right ml-2">
                        <a class="btn btn-dark" id="btReport"><i class="fa fa-file-archive" aria-hidden="true"></i> </a>
                    </div>

                </div>
            </div>
        </div>
    </form>
    @include('gi2.filter')
    @include('gi2.report')


@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $("#btFilter").click(function () {
                $("#filter").toggleClass("d-none");
            });
        });
        $(document).ready(function () {
            $("#btReport").click(function () {
                $("#report").toggleClass("d-none");
            });
        });
    </script>
@stop

