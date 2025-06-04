<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Nome do DOC</th>
                <th>Data</th>
                <th>Upload</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableDocs">
            @if(isset($person) && $person->docs->count())
                @foreach($person->docs as $index => $doc)
                    <tr>
                        <td>{{$doc->nome_doc}}</td>
                        <td>{{$doc->data}}</td>
                        <td>
                            @if($doc->upload)
                                <a href="{{ asset($doc->upload) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            @else
                                <span class="text-muted">Sem arquivo</span>
                            @endif
                        </td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="removeExistingDoc(this, {{ $doc->id }})"
                               title="Remover" style="cursor: pointer;"></i></td>
                        <input type="hidden" name="existing_docs[{{ $index }}]" value="{{json_encode($doc)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        // Inicializa o índice baseado nos documentos já existentes
        let docIndex = {{ isset($person) && $person->docs ? $person->docs->count() : 0 }};
        
        // Array para rastrear documentos removidos
        let removedDocs = [];

        // Atualiza o label do arquivo quando selecionado
        $('#doc_upload').on('change', function() {
            const fileName = $(this)[0].files[0]?.name || 'Escolher arquivo PDF...';
            $(this).next('.custom-file-label').text(fileName);
        });

        // Função para remover documento existente
        function removeExistingDoc(element, docId) {
            // Adiciona o ID do documento ao array de removidos
            if (docId && !removedDocs.includes(docId)) {
                removedDocs.push(docId);
            }
            
            // Remove a linha visualmente
            $(element).parent().parent().remove();
            
            // Atualiza o campo hidden com os documentos removidos
            updateRemovedDocsField();
        }

        // Função para atualizar o campo hidden com documentos removidos
        function updateRemovedDocsField() {
            // Remove o campo existente se houver
            $('input[name="removed_docs"]').remove();
            
            // Cria novo campo hidden com os documentos removidos
            if (removedDocs.length > 0) {
                let input = document.createElement('input');
                input.setAttribute('type', 'hidden');
                input.setAttribute('name', 'removed_docs');
                input.setAttribute('value', JSON.stringify(removedDocs));
                
                // Adiciona dentro do formulário específico
                const form = document.getElementById('form');
                if (form) {
                    form.appendChild(input);
                } else {
                    // Fallback para o primeiro formulário encontrado
                    const firstForm = document.querySelector('form');
                    if (firstForm) {
                        firstForm.appendChild(input);
                    }
                }
                
                console.log('Campo removed_docs atualizado:', removedDocs);
            }
        }

        function addDoc() {
            const nomeDoc = document.getElementById('doc_nome_doc').value;
            const data = document.getElementById('doc_data').value;
            const uploadInput = document.getElementById('doc_upload');
            const file = uploadInput.files[0];

            if (nomeDoc.trim() === '') {
                alert('O campo Nome do DOC é obrigatório.');
                return;
            }

            // Função para converter data do formato DD/MM/YYYY para YYYY-MM-DD
            function convertDate(dateStr) {
                if (!dateStr || dateStr.trim() === '') return '';
                
                // Verifica se está no formato DD/MM/YYYY
                const dateParts = dateStr.split('/');
                if (dateParts.length === 3) {
                    const [day, month, year] = dateParts;
                    return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                }
                return dateStr; // Retorna como está se não for no formato esperado
            }

            // Cria o objeto do Doc
            const docData = {
                nome_doc: nomeDoc,
                data: convertDate(data),
                file_index: docIndex
            };

            // Cria input hidden para enviar os dados - usa nome único para novos documentos
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `new_docs[${docIndex}]`);
            input.setAttribute('value', JSON.stringify(docData));

            // Se há arquivo, cria input file para envio
            let fileInput = null;
            let fileName = 'Sem arquivo';
            if (file) {
                fileInput = document.createElement('input');
                fileInput.setAttribute('type', 'file');
                fileInput.setAttribute('name', `doc_uploads[${docIndex}]`);
                fileInput.setAttribute('style', 'display: none;');
                
                // Cria um novo input file e transfere o arquivo
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;
                
                fileName = `<a href="#" class="btn btn-sm btn-primary"><i class="fas fa-file-pdf"></i> ${file.name}</a>`;
            }

            // Cria a linha na tabela
            let table = document.querySelector('#tableDocs');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${nomeDoc}</td>
                <td>${data}</td>
                <td>${fileName}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            if (fileInput) {
                tr.appendChild(fileInput);
            }
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('doc_nome_doc').value = '';
            document.getElementById('doc_data').value = '';
            document.getElementById('doc_upload').value = '';
            document.querySelector('#doc_upload').nextElementSibling.textContent = 'Escolher arquivo PDF...';

            // Incrementa o índice
            docIndex++;
        }
    </script>
@endpush 