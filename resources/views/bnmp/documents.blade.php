{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 04/05/2023
 * @copyright NIP CIBER-LAB @2023
--}}
<div class="row">
    <div class="col-sm-12">
        <div class="card-header bg-light">
            <strong>Documentos</strong>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Número</th>
                <th>Tipo</th>
                <th>Outros nomes</th>
                <th>Orgão emissor</th>
                <th>UF</th>
            </tr>
            </thead>
            <tbody>
            @foreach($person['documento'] as $document)
                <tr>
                    <td>{{ $document['numeroDocumento'] }}</td>
                    <td>{{ $document['tipoDocumento'] }}</td>
                    <td>{{ $document['outrosNomes'] }}</td>
                    <td>{{ $document['textoOrgaoEmissor'] }}</td>
                    <td>{{ $document['uf'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
