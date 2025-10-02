@extends('adminlte::page')
<!--define titulo da pagina-->
@section('title','Cadastro de Pessoa')
<!--define o conteudo do header-->
@section('content_header')
    <div class="card card-dark">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between col-md-12">
                <div>
                    <h1 class="h1 text-light">Cadastro de Pessoa</h1>
                </div>
                <div>
                    @if($person->warrant == 1 && !$person->active_orcrim)
                        <span class="text-danger text-lg p-1 pr-4 pl-4 rounded" style="background-color: #FFC720">
                        Atenção: Pessoa com mandado de prisão - Consultar na base nacional.
                    </span>
                    @endif
                </div>
                <div>
                    <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
                       type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
                </div>
            </div>
        </div>
    </div>
@endsection
<!--define o conteudo da pagina-->
@section('content')
    {{-- Incluir partials adicionais que não contenham layout (ex: person.partials.detran, person.partials.adepara).
         A inclusão ocorre se a variável view_base for igual a 'detran'/'adepara' ou se as variáveis
         'detran'/'adepara' estiverem definidas para compatibilidade antiga. --}}
    @if((isset($view_base) && $view_base === 'adepara') || isset($adepara))
        @include('person.partials.adepara')
    @elseif((isset($view_base) && $view_base === 'detran') || isset($detran))
        @include('person.partials.detran')
    @elseif((isset($view_base) && $view_base === 'dadoscadastrais') || isset($dadosCadastrais))
        @include('person.partials.dadoscadastrais')
    @elseif((isset($view_base) && $view_base === 'quadro_societario') || isset($quadroSocietario))
        @include('person.partials.quadro_societario')
    @elseif((isset($view_base) && $view_base === 'semas') || isset($semas))
        @include('person.partials.semas')
    @endif
    @include('person.view.content')
@endsection
