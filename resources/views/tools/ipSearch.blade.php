{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 31/10/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Pesquisa de IP')
<x-page-header title="Pesquisa de IP">
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <form action="{{route('search.ip')}}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <x-adminlte-input type="text" name="ip" class="form-control" id="ip" placeholder="Informe o IP"
                                          value="{{old('ip')}}"
                                          required/>
                    </div>
                    <div class="ml-2">
                        <x-adminlte-button
                            type="submit"
                            icon="fas fa-search"
                            theme="secondary"
                            label="Pesquisar"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @isset($data)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td>IP</td>
                            <td>{{ $data->ip }}</td>
                        </tr>
                        <tr>
                            <td>Cidade</td>
                            <td>{{ $data->city }}</td>
                        </tr>
                        <tr>
                            <td>Região</td>
                            <td>{{ $data->region }}</td>
                        </tr>
                        <tr>
                            <td>País</td>
                            <td>{{ $data->country }}</td>
                        </tr>
                        <tr>
                            <td>Latitude/Longitude</td>
                            <td>
                                {{ $data->latitude }},{{ $data->longitude }}
                                <a class="ml-2" href="https://www.google.com/maps/place/{{$data->loc}}" target="_blank">
                                    <i class="fas fa-map-marked"></i>
                                </a>
                            </td>

                        </tr>
                        <tr>
                            <td>Organização</td>
                            <td>{{ $data->org }}</td>
                        </tr>
                        <tr>
                            <td>Código Postal</td>
                            <td>{{ $data->postal }}</td>
                        </tr>
                        <tr>
                            <td>Fuso Horário</td>
                            <td>{{ $data->timezone }}</td>
                        </tr>
                        <tr>
                            <td>Nome do País</td>
                            <td>{{ $data->country_name }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endisset
@endsection
