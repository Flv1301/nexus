@extends('adminlte::page')
@section('title','Suporte em Vídeo')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('content_header')
    <div class="container">
        <h1 style="margin-bottom: 10px">Catálogo de Vídeos</h1>

        @foreach($categories as $category)
            <div class="category">
                <h2>{{ $category->name }}</h2>

                @if($category->videos->isEmpty())
                    <p>Não há vídeos nesta categoria.</p>
                @else
                    <ul>
                        @foreach($category->videos as $video)
                            <li>
                                <strong>{{ $video->name }}</strong><br>
                                <p>{{ $video->about }}</p>
                                <p>Tempo: {{ $video->time }}</p>
                                <a href="{{ route('support.videos.player', ['filename' => $video->url]) }}">Assistir</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>
@endsection
