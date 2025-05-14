@extends('adminlte::page')
@section('title', 'GI2')
@section('plugins.Datatables', true)

@section('content')

            <div class="card">
                <div class="card-body">
                    <div class="card">
                        <div class="card-header">
                            Abrir Mapa
                        </div>
                        <div class="card-body">
                            <form action="{{ route('erb.show.honeycomb') }}" class="row" method="GET">
                                <div class="col-md-4">
                                    <label for="municipio">Município:</label>
                                    <select id="municipio" name="city" class="form-control">

                                        @foreach ($municipios as $municipio)
                                            <option value="{{ $municipio }}">{{ $municipio }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="operadora">Operadora:</label>
                                    <select id="operadora" name="operator" class="form-control">
                                        <option value="Claro">Claro</option>
                                        <option value="Vivo">Vivo</option>
                                        <option value="TIM">Tim</option>

                                    </select>
                                </div>

                                <div class="col-md-4 align-self-end">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Importar Histórico
                        </div>
                        <div class="card-body">
                            <form action="" class="row">
                                <div class="col-md-4">
                                    <label for="municipio">Historico:</label>
                                    <input type="file" class="form-control ">
                                </div>

                                <div class="col-md-4">
                                    <label for="operadora">Operadora:</label>
                                    <select id="operadora" name="operadora" class="form-control">
                                        <option value="Claro">Claro</option>
                                        <option value="Vivo">Vivo</option>
                                        <option value="TIM">Tim</option>

                                    </select>
                                </div>

                                <div class="col-md-4 align-self-end">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

@endsection

