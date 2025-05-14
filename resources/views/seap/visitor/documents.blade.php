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
        @foreach($person->documents->unique() as $document)
            <div class="callout callout-info">
                <h5>{{$document->documentotipo_descricao}}</h5>
                <table>
                    <tr>
                        <td>Numero:</td>
                        <td>{{$document->visitantedocumento_numero}}</td>
                    </tr>
                    <tr>
                        <td>data de emissão:</td>
                        <td>{{\Illuminate\Support\Carbon::parse($document->visitantedocumento_dataemissao)->format('d/m/Y')}}</td>
                    </tr>
                    <tr>
                        <td>órgão emissor:</td>
                        <td>{{$document->visitantecumento_orgaoexpedidor}}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
</div>
