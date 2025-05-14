@extends('adminlte::page')

@section('title','Suporte em Vídeo')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)

@section('content_header')
    <div class="video-container">
        <video class="video-player" controls>
            <source src="{{ route('support.videos.stream', ['filename' => $filename]) }}" type="video/mp4">
            Seu navegador não suporta o formato de vídeo.
        </video>
    </div>
@endsection

@push('css')
    <style>
        .video-container {
            width: 80%;
            height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .video-player {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    </style>
@endpush
