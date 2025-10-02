<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Tipo de Vínculo</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableVisitantes">
            @if(isset($person) && $person->visitantes->count())
                @foreach($person->visitantes as $visitante)
                    <tr>
                        <td>{{$visitante->nome}}</td>
                        <td>{{$visitante->cpf}}</td>
                        <td>{{$visitante->tipo_vinculo}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" name="visitantes[]" value="{{json_encode($visitante)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        let visitanteIndex = {{ isset($person) && $person->visitantes ? $person->visitantes->count() : 0 }};

        function addVisitante() {
            const nome = document.getElementById('visitante_nome').value;
            const cpf = document.getElementById('visitante_cpf').value;
            const tipoVinculo = document.getElementById('visitante_tipo_vinculo').value;

            if (nome.trim() === '') {
                alert('O campo Nome é obrigatório.');
                return;
            }

            if (tipoVinculo.trim() === '') {
                alert('O campo Tipo de Vínculo é obrigatório.');
                return;
            }

            // Cria o objeto do Visitante
            const visitanteData = {
                nome: nome,
                cpf: cpf,
                tipo_vinculo: tipoVinculo
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `visitantes[${visitanteIndex}]`);
            input.setAttribute('value', JSON.stringify(visitanteData));

            // Cria a linha na tabela
            let table = document.querySelector('#tableVisitantes');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${nome}</td>
                <td>${cpf}</td>
                <td>${tipoVinculo}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('visitante_nome').value = '';
            document.getElementById('visitante_cpf').value = '';
            document.getElementById('visitante_tipo_vinculo').value = '';

            // Incrementa o índice
            visitanteIndex++;
        }
    </script>
@endpush 