{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 28/04/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="card card-default">
    <div class="card-header">
        <h3 class="card-title">Documentos Cadastrados</h3>
    </div>
    <div class="card-body">
        @foreach($person->documents as $document)
            <div class="callout callout-info">
                <h5>{{$document->documentotipo_descricao}}</h5>
                <table>
                    <tr>
                        <td>CPF:</td>
                        <td>{{$document->cpf_numero}}</td>
                    </tr>
                    <tr>
                        <td>RG:</td>
                        <td>{{$document->rg_numero}}</td>
                    </tr>
                   <tr>
                        <td>CNH:</td>
                        <td>{{$document->cnh_numero}}</td>
                    </tr>
                    <tr>
                        <td>Titulo Eleitor:</td>
                        <td>{{$document->tituloeleitor_numero}}</td>
                    </tr>
                    <tr>
                        <td>SUS:</td>
                        <td>{{$document->cartaosus_numero}}</td>
                    </tr>
                    <tr>
                        <td>Carteira Trabalho:</td>
                        <td>{{$document->carteiratrabalho_numero}}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
</div>
