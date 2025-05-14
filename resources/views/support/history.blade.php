{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Solicitação de Suporte')
<x-page-header title="Histórico de Solicitação do Suporte {{$support->title}}">
    <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
       type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
</x-page-header>
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="timeline">
                        @php $date = null; @endphp
                        @foreach($responses->reverse() as $response)
                            @if($response->created_at->format('dmY') != $date)
                                <div class="time-label">
                                    <span class="bg-gradient-light">{{$response->created_at->format('d/m/Y')}}</span>
                                </div>
                            @endif
                            @php $date = $response->created_at->format('dmY') @endphp
                            <div>
                                <i class="fas fa-arrow-down bg-gradient-gray-dark"></i>
                                <div class="timeline-item">
                                    <span class="time"><a class="pr-3 text-lg text-primary"
                                                          href="{{route('support.response.show', $response->id)}}"><i
                                                class="fas fa-eye text-black"></i></a>
                                        <i class="fas fa-clock"></i> {{$response->created_at->format('d/m/Y H:i')}}</span>
                                    <h3 class="timeline-header"><strong>Status </strong> <span
                                            class='{{\App\Enums\StatusSupportEnum::from($support->status)->style()}}'>{{$support->status}}</span>
                                        | <Strong>Usuário</Strong> <span
                                            class="text-info">{{$response->user->name}}</span>
                                    </h3>
                                    <div class="timeline-body">
                                        {!! $response->response !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
