@php

@endphp
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
{{--    <meta name="viewport" content="width=device-width, initial-scale=1">--}}





    <style>
       @include('qualify.letter.templateletter.bootstrap')
    </style>





    <title>
        Solicatação de Dados Cadastrais   </title>



    <!-- CSS only -->


</head>
<body>




    <div class="">
        <img width="50px" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images'. DIRECTORY_SEPARATOR .'logopc.png'))) }}" class="rounded float-left" alt="...">
        <img width="50px" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images'. DIRECTORY_SEPARATOR .'brasaopc.png'))) }}" class="rounded float-right" alt="...">



    <div class="row">
        <div class="d-flex flex-nowrap w-100">





        </div>

    </div>
        <p class="text-center">
            POLÍCIA CIVIL DO PARÁ <br>
            NÚCLEO DE INTELIGÊNCIA POLICIAL

        </p>
    </div>
<div class="content layout-fixed">
        <div class="container">

                <div class="">
                   <div class="col-12">
                       <p class="text-left ">OFÍCIO Nº 1090/2023/NIP/CYBER-LAB</p>
                       <p class="text-right text-end">Belém/PA, {{$data->semana}}, {{$data->dia}} de {{$data->mes}} de {{$data->ano}}.</p>
                       <p>A (o) Senhor (a)
                           Gerente/Diretor(a)
                           {{$companyName}}
                       </p>
                       <strong class="font-weight-bold">Assunto:  Solicitação de dados cadastrais</strong>
                       <p>
                           Prezado(a) Senhor(a),
                       </p>
                       <p class="border">
                           DESPACHO: A recusa no recebimento deste implicará identificação do recusante, havendo intimação imediata para comparecimento à Delegacia, informando-se superior hierárquico. CONDUÇÃO: Havendo recusa, novamente, de informação da qualificação do primeiro ou primeira servidor (a) ou funcionário (a) deste local em face do recebimento deste oficio será, também, passível de prisão criminal em face do crime de desobediência, de acordo com o art. 330, do Código Penal, além da prática do art. 68, do Decreto-Lei nº 3.688/1941 – Lei das Contravenções Penais – recusa de dados sobre a própria identificação ou qualificação
                       </p>
                       <p class="text-justify">
                           <ol class="text-justify">
                             <li class="text-justify">Incide o presente ato sobre as atribuições de Polícia Judiciária, conforme o art. 144, §§ 1º e 4º da Constituição Federal, ex vi arts. 3º, 4º, 5º, 6º, todos do Código de Processo Penal, c/c art. 2º, § 2º da Lei 12.830/13, c/c art. 17-B, da Lei 12.683/12. Coalescido a este ofício despacho (supra) sobre o método de notificação e finalização deste procedimento, caso haja problemática para seu desfecho.</li>
                             <li class="text-justify">Há expediente investigativo para a caracterização de prática criminosa onde se objetiva a constituição de autoria e materialidade delitiva mediante coalescimento de informações para complementar a atividade em tela.</li>
                             <li class="text-justify">Cediça a inexistência de proteção legal por sigilo de dados cadastrais e informação de numerais em si mesmos considerados, de acordo com o conteúdo legislativo acima demonstrado e a manifestação da jurisprudência dominante. (STF.Pleno.RE nº 418.416--‐8; STF, MS 23452 / RJ; RTJ 179/225, 270; TRF 1ªRegião. 3ª Turma. HC nº 2007.01.00.003265--‐4/DF</li>
                             <li class="text-justify"><strong>Consoante os motivos expostos e, reconhecido legalmente o poder conferido à Polícia Judiciária de obter exclusivamente as informações cadastrais e numerais cadastrados, os quais informam a qualificação pessoal, filiação e endereço, além de numerais cadastrados junto às operadoras de telefonia móvel, independente de ordem judicial, mantidas pelas empresas telefônicas, instituições financeiras, provedores de internet, administradora de cartão de crédito e Justiça Eleitoral (art. 17-B, da lei 12.683/12), REQUISITO a Vossa Senhoria, ou seu substituto que, NO PRAZO MÁXIMO DE 24 HORAS, formalize o envio e a entrega dos DADOS CADASTRAIS ATINENTES AO(s)
                                     {{$wyh}}(S) INFRA MENCIONADO:
                                 </strong>

                             </li>
                           <table class="table">
                               <thead>
                               <th>De</th>
                               <th>Até</th>
                               <th>IMEI</th>

                               </thead>
                               <tbody>

                               @foreach($id as $item)
                                   <tr>

                                   <td><?php echo date('d/m/Y',strtotime($item->dt_registro))?></td>
                                   <td><?php echo date('d/m/Y')?></td>
                                   <td>{{$item->imei}}</td>
                                   {{$item->imei}}
                                   </tr>
                               @endforeach
                               </tbody>
                           </table>

                           <li class="text-justify">Por fim, os dados de cadastro deverão ser enviados para o e-mail funcional da autoridade policial requisitante adrienne.pessoa@policiacivil.pa.gov.br com cópia para hitalo.hamonn@policiacivil.pa.gov.br.</li>
                           <li class="text-justify">A possível renitência em cumprir esta requisição poderá gerar responsabilidade penal em face da prática dos crimes de prevaricação e/ou desobediência com infração das disposições legais, como bem destacam os arts. 319 e 330, do Código Penal.</li>
                       </ol>

                       </p>
                            <p>
                                <strong class="text-center">
                                    <img width="400px" src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('images'. DIRECTORY_SEPARATOR .'assinaturadpc.jpeg'))) }}" class="rounded text-center align-content-center" alt="...">
                               </strong>

                            </p>
                   </div>
                </div>




            </div>

    </div>

</div>





</div>


</body>
</html>


