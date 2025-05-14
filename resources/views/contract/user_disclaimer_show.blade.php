<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <title>Termo de Responsabilidade</title>

</head>
<body style="background-color: #f8f9fa;">
<x-page-messages/>
<div class="container-fluid mt-lg-5 text-center">
    <img src="{{asset('images/policia.png')}}" class="mb-3">
    <h1 class="mb-4">Termo de Responsabilidade</h1>
    <p class="mb-4">Para acessar o sistema é necessário imprimir, assinar e enviar o <a
            href="{{ route('user.access.document.download') }}" target="_blank">Termo de Responsabilidade</a>.</p>
    <div class="card w-50 mx-auto" style="background-color: #f8f9fa;">
        <div class="card-header">Formulário de Envio.</div>
        <div class="card-body" style="background-color: #f8f9fa;">
            <form action="{{ route('user.access.document.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="TERMO DE RESPONSABILIDADE" />
                <x-adminlte-input-file igroup-size="md" name="document"
                                       placeholder="Selecione o arquivo."
                                       label="Documento">
                    <x-slot name="prependSlot">
                        <div class="input-group-text">
                            <i class="fas fa-upload"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>
                <x-adminlte-button type="submit" label="Enviar Documento" theme="primary" icon="fas fa-paper-plane"/>
            </form>
        </div>
    </div>
    <footer class="mt-lg-5">
        <p class="text-info text-lg">O setor responsável irá verificar o documento enviado para a liberação do acesso ao sistema.</p>
        <span>Laboratório de Inteligência Cibernética - CIBERLAB @2023</span>
    </footer>
</div>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('vendor/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init()
    })</script>
</body>
</html>
