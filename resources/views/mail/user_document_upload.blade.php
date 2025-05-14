<!DOCTYPE html>
<html lang="pt_BR">
<head>
    <meta charset="UTF-8">
    <title>Documentação de Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 1080px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        img.corner-image {
            top: 20px;
            right: 20px;
            max-width: 300px;
            height: auto;
        }

        span {
            display: block;
            margin-bottom: 10px;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <img class="corner-image" src="https://www.pc.pa.gov.br/images/logo_pcpa_white.png" alt="Identificação">
    <h1>Documentação de Usuário</h1>
</header>
<div class="container">
    <span>Olá.</span>
    <span>O Usuário <strong>{{$user->name}}</strong>  matrícula <strong>{{$user->registration}}</strong> enviou documentação para validação!</span>
</div>
</body>
</html>

