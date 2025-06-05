{{--
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 27/05/2025
 * @copyright NIP CIBER-LAB @2025
--}}
<div class="card col-md-12">
    <div class="card-header">
        <h3 class="card-title">DOCS</h3>
    </div>
    <div class="card-body">
        @if($person->docs->count() > 0)
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Nome do DOC</th>
                    <th>Data</th>
                    <th>Upload</th>
                </tr>
                </thead>
                <tbody>
                @foreach($person->docs as $doc)
                    <tr>
                        <td>{{ $doc->nome_doc }}</td>
                        <td>{{ $doc->data }}</td>
                        <td>
                            @if($doc->upload)
                                <a href="{{ route('person.serve.document', ['personId' => $person->id, 'docId' => $doc->id]) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            @else
                                <span class="text-muted">Sem arquivo</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">
                <i class="icon fas fa-info"></i>
                Nenhum documento cadastrado.
            </div>
        @endif
    </div>
</div> 