{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 19/01/2023
 * @copyright NIP CIBER-LAB @2023
--}}
@extends('adminlte::page')
@section('title','Solicitação de Suporte')
@section('plugins.Summernote', true)
<x-page-header title="Resposta da Solicitação de Suporte - {{$response->support->title}}">
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
                                class="{{\App\Enums\StatusSupportEnum::from($response->support->status)->style()}} text-md">{{$response->support->status}}</span></label>
                    </div>
                    <div class="form-group col-md-4">
                        <x-adminlte-input label="Título" id="title" name="title"
                                          value="{{$response->support->title}}" disabled/>
                    </div>
                    <div class="form-group">
                        <x-adminlte-text-editor name="description" disabled
                                                placeholder="Descreva sua solicitação." label="Solicitação">
                            {{$response->response}}
                        </x-adminlte-text-editor>
                    </div>
                    @if($response->images->count())
                        <div class="form-group">
                            <label>Arquivos</label>
                            <div class="card">
                                <div class="card-body" id="images-preview">
                                    @foreach ($response->images()->get() as $count => $image)
                                        @php
                                            $path = storage_path('app/' . $image->path);
                                            $type = '';
                                            $content = '';
                                            if (\Illuminate\Support\Facades\Storage::exists($path)){
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
            </div>
        </div>
    </div>
@endsection
