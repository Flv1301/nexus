{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Solicitação de Suporte')
@section('plugins.Summernote', true)
<x-page-header title="Solicitação de Suporte - {{$support->title}}">
    <a href="{{ url()->previous() }}" id="history" class="btn btn-info"
       type="button"><i class="fas fa-sm fa-backward p-1"></i>Voltar</a>
</x-page-header>
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card">
                <div class="card-body">
                    <div class="form-group col-md-4">
                        <label>Status: <span
                                class="{{\App\Enums\StatusSupportEnum::from($support->status)->style()}} text-md">{{$support->status}}</span></label>
                    </div>
                    <div class="form-group col-md-4">
                        <x-adminlte-input label="Título" id="title" name="title"
                                          value="{{$support->title}}" disabled/>
                    </div>
                    <div class="form-group">
                        <x-adminlte-text-editor name="description" disabled
                                                placeholder="Descreva sua solicitação." label="Solicitação">
                            {{$support->description}}
                        </x-adminlte-text-editor>
                    </div>
                    @if($support->images->count())
                        <div class="form-group">
                            <label>Arquivos</label>
                            <div class="card">
                                <div class="card-body" id="images-preview">
                                    @foreach ($support->images()->get() as $count => $image)
                                        @php
                                            $path = storage_path('app/' . $image->path);
                                            $type = '';
                                            $content = '';
                                            if (\Illuminate\Support\Facades\Storage::exists($image->path)){

                                            $type = mime_content_type($path);
                                            $content = base64_encode(file_get_contents($path));
                                            }
                                        @endphp
                                        <a href="#" label="Open Modal" data-toggle="modal"
                                           data-target="#modalMin-{{$count}}">
                                            <object class="img-thumbnail" width="200px" height="200px"
                                                    data="data:{{$type}};base64,{{$content}}" type="{{$type}}">
                                                <span class="text-black text-lg">Ooops! O navegador não suporta esse tipo de arquivo.</span>
                                            </object>
                                        </a>
                                        <x-adminlte-modal size="xl" id="modalMin-{{$count}}"
                                                          title="{{$image->description}}" style="text-align: center">
                                            <object width="800" data="data:{{$type}};base64,{{$content}}"
                                                    type="{{$type}}">
                                                <span class="text-black text-lg">Ooops! O navegador não suporta esse tipo de arquivo.</span>
                                            </object>
                                        </x-adminlte-modal>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    @can('suporte resposta')
                        @if($support->status !== 'Fechado')
                            <a href="{{route('support.torespond', $support)}}" id="torespond" class="btn btn-success"
                               type="button"><i class="fas fa-md fa-save p-1"></i>Responder</a>
                        @endif
                    @endcan
                    <a href="{{route('support.history', $support)}}" id="history" class="btn btn-info"
                       type="button"><i class="fas fa-calendar-times p-1"></i>Histórico</a>
                </div>
            </div>
        </div>
    </div>
    @if($support->responses->count())
        <div class="card">
            <div class="card-header">
                <span
                    class="text-md text-info">Última Resposta do Usuário {{($support->responses->max())->user->name}}
                </span>
            </div>
            <div class="card-body">
                <p>{!! ($support->responses->max())->response !!}</p>
            </div>
        </div>
    @endif
@endsection
