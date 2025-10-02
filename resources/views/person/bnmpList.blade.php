<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>N. Mandado</th>
                <th>Órgão Expedidor</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableBnmp">
            @if(isset($person) && $person->bnmps->count())
                @foreach($person->bnmps as $bnmp)
                    <tr>
                        <td>{{$bnmp->numero_mandado}}</td>
                        <td>{{$bnmp->orgao_expedidor}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" name="bnmps[]" value="{{json_encode($bnmp)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        let bnmpIndex = {{ isset($person) && $person->bnmps ? $person->bnmps->count() : 0 }};

        function addBnmp() {
            const numeroMandado = document.getElementById('bnmp_numero_mandado').value;
            const orgaoExpedidor = document.getElementById('bnmp_orgao_expedidor').value;

            if (numeroMandado.trim() === '') {
                alert('O campo N. Mandado é obrigatório.');
                return;
            }

            // Cria o objeto do BNMP
            const bnmpData = {
                numero_mandado: numeroMandado,
                orgao_expedidor: orgaoExpedidor
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `bnmps[${bnmpIndex}]`);
            input.setAttribute('value', JSON.stringify(bnmpData));

            // Cria a linha na tabela
            let table = document.querySelector('#tableBnmp');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${numeroMandado}</td>
                <td>${orgaoExpedidor}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('bnmp_numero_mandado').value = '';
            document.getElementById('bnmp_orgao_expedidor').value = '';

            // Incrementa o índice
            bnmpIndex++;
        }
    </script>
@endpush 