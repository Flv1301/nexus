<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PGE Consult</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px }
        label { display:block; margin-bottom:6px }
        input { padding:6px; margin-right:8px }
    </style>
</head>
<body>
    <h1>Consulta PGE</h1>
    @include('components.pge-consult')
    @if(isset($partial))
        <div class="pge-standalone">
            {!! $partial !!}
        </div>
    @endif
</body>
</html>
