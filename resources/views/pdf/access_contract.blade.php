<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>Termo De Responsabilidade</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            width: 100%;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            display: flex;
            align-items: center;
        }

        .logo {
            max-width: 80px;
            margin: 5px;
        }

        .field-label {
            font-weight: bold;
        }

        .table-bordered {
            border: 1px solid #ccc;
            border-collapse: collapse;
            width: 100%;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <table class="w-100">
        <tr>
            <td>
                <img src="{{ public_path('images/policia.png') }}" class="logo" alt="Logo da Polícia Civil do Pará">
            </td>
            <td class="text-center">
                <h1 class="h3">Polícia Civil do Estado do Pará</h1>
                <span style="font-size: 12px">Av. Gov Magalhães Barata, 209 - Nazaré, Belém - PA, 66040-170</span>
            </td>
            <td>
                <img src="{{ public_path('images/brasaopc.png') }}" class="logo" alt="Brasão da Polícia Civil do Pará">
            </td>
        </tr>
    </table>
    <h1 class="h2 text-center">Termo De Responsabilidade</h1>
    <div class="section-title">IDENTIFICAÇÃO</div>
    <table class="table table-bordered">
        <tr>
            <td class="field-label">Nome:</td>
            <td colspan="3">{{$user->name}}</td>
        </tr>
        <tr>
            <td class="field-label">CPF:</td>
            <td>{{$user->cpf}}</td>
            <td class="field-label">Data de Nascimento:</td>
            <td>{{$user->birth_date}}</td>
        </tr>
    </table>

    <div class="section-title">INFORMAÇÕES FUNCIONAIS</div>
    <table class="table table-bordered">
        <tr>
            <td class="field-label">Órgão de Lotação:</td>
            <td colspan="3">POLÍCIA CIVIL DO ESTADO DO PARÁ</td>
        </tr>
        <tr>
            <td class="field-label">Matrícula:</td>
            <td>{{$user->registration}}</td>
            <td class="field-label">Login:</td>
            <td>{{$user->email}}</td>
        </tr>
        <tr>
            <td class="field-label">Unidade:</td>
            <td>{{$user->unity->name}}</td>
            <td class="field-label">Setor:</td>
            <td>{{$user->sector->name}}</td>
        </tr>
        <tr>
            <td class="field-label">Cargo:</td>
            <td>{{$user->office}}</td>
            <td class="field-label">Função:</td>
            <td>{{$user->role}}</td>
        </tr>
        <tr>
            <td class="field-label">Telefone Celular:</td>
            <td>({{$user->ddd}}) {{$user->telephone}}</td>
            <td class="field-label">E-mail:</td>
            <td>{{$user->email}}</td>
        </tr>
    </table>
    <div class="section-title text-center">Termo de Responsabilidade</div>
    <p class="text-justify">Declaro serem verdadeiras as informações prestadas, estando ciente do que estabelecem os
        artigos 153, 313-A, 313-B, 299, 325 e 327 do CÓDIGO PENAL BRASILEIRO e Art. 6º da lei nº 13.709/2018 (LGPD).
        Declaro, ainda, estar ciente da responsabilidade do sigilo sobre a informação que tenho acesso por meio do
        sistema Hydra, assim como pela utilização ou mau uso da minha senha, seja qual for a circunstância, podendo
        acarretar na perda de privilégios ou bloqueio do usuário, além da apuração criminal da conduta.</p>
    <p class="section-title text-center">A mudança de unidade ou setor do servidor, deve ser comunicada imediatamente ao
        setor responsável do sistema.</p>
    <table class="w-100">
        <tr>
            <td>
                <div>____________________________________</div>
                <div>Data e Assinatura do Usuário</div>
            </td>
            <td>
                <div>____________________________________</div>
                <div>Data e Assinatura da Chefia Imediata</div>
            </td>
        </tr>
    </table>
    <div class="section-title">Observação:</div>
    <p>Esta solicitação deverá ser necessariamente assinada e datada, tanto pelo beneficiário da conta como por sua
        chefia imediata para a liberação do acesso ao sistema Hydra.</p>

    <div class="section-title">LABORATÓRIO DE INTELIGÊNCIA CIBERNÉTICA (CIBERLAB)</div>
    <p>E-mail: ciberlab@nippcpara.com</p>
</div>
</body>
</html>
