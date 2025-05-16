@extends('adminlte::page')
@section('title','Pesquisa PIX')
<x-page-header title="Pesquisa Dados PIX">
</x-page-header>
@section('content')
    <x-page-messages/>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('pesquisa.pix.search') }}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <x-adminlte-input type="text" name="key_pix" class="form-control" id="key_pix"
                                          value="{{old('key_pix')}}" placeholder="Informe a chave pix ou CPF/CNPJ"
                                          required/>
                    </div>
                    <div class="col-md-4">
                        <x-adminlte-input type="text" name="motivation" class="form-control" id="motivation"
                                          value="{{old('motivation')}}"
                                          placeholder="Informe o motivo para sua pesquisa." required/>
                    </div>
                    <div class="ml-2">
                        <x-adminlte-button type="submit" icon="fas fa-search" theme="secondary" label="Pesquisar"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-info">
                        Observação: Ao usar um número de telefone como chave PIX, não se esqueça de incluir o código do
                        país, '+55', antes do número. CPF deve ser apenas números.
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="search_type" id="search_pix" value="pix"
                                   checked>
                            <label class="form-check-label" for="search_pix">
                                Chave Pix
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="search_type" id="search_cpf" value="cpf">
                            <label class="form-check-label" for="search_cpf">
                                Vínculo CPF/CNPJ
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @isset($data)
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ __('Resultado da Pesquisa') }}
                        {{--                        <button onclick="window.print()" class="btn btn-primary no-print">--}}
                        {{--                            <i class="fas fa-print"></i> Imprimir--}}
                        {{--                        </button>--}}
                    </div>
                    <div class="card-body">
                        @if(isset($data['error']))
                            <div class="alert alert-danger">
                                {{ $data['error'] }}
                            </div>
                        @else
                            <div class="table-responsive mb-4">
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                    @foreach ($data as $key => $value)
                                        @if (!is_array($value) && !empty($value))
                                            <tr>
                                                <td>
                                                    <strong>{{ ucwords(str_replace('_', ' ', preg_replace('/(?<!\ )[A-Z]/', ' $0', $key))) }}</strong>
                                                </td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if (isset($data['eventosVinculo']))
                                @foreach ($data['eventosVinculo'] as $evento)
                                    <div class="table-responsive mb-4">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th colspan="2"><strong>Evento de Vínculo</strong></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($evento as $key => $value)
                                                @if (!is_array($value) && !empty($value))
                                                    <tr>
                                                        <td>
                                                            <strong>{{ ucwords(str_replace('_', ' ', preg_replace('/(?<!\ )[A-Z]/', ' $0', $key))) }}</strong>
                                                        </td>
                                                        <td>{{ $value }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            @endif

                            @if ($searchType === 'cpf')
                                @foreach ($data as $vinculo)
                                    @if (isset($vinculo['eventosVinculo']))
                                        @foreach ($vinculo['eventosVinculo'] as $evento)
                                            <div class="table-responsive mb-4">
                                                <table class="table table-striped table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="2"><strong>Evento de Vínculo</strong></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($evento as $key => $value)
                                                        @if (!is_array($value) && !empty($value))
                                                            <tr>
                                                                <td>
                                                                    <strong>{{ ucwords(str_replace('_', ' ', preg_replace('/(?<!\ )[A-Z]/', ' $0', $key))) }}</strong>
                                                                </td>
                                                                <td>{{ $value }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endisset
@endsection

@push('css')
    {{--    <style>--}}
    {{--        @media print {--}}
    {{--            .no-print {--}}
    {{--                display: none;--}}
    {{--            }--}}
    {{--            .card-header {--}}
    {{--                display: flex;--}}
    {{--                justify-content: center;--}}
    {{--                align-items: center;--}}
    {{--            }--}}
    {{--        }--}}
    {{--    </style>--}}
@endpush
