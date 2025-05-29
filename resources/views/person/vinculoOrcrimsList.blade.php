<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Alcunha</th>
                <th>CPF</th>
                <th>Tipo de Vínculo</th>
                <th>Orcrim</th>
                <th>Cargo</th>
                <th>Área de Atuação</th>
                <th>Matrícula</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableVinculoOrcrims">
            @if(isset($person) && $person->vinculoOrcrims->count())
                @foreach($person->vinculoOrcrims as $vinculo)
                    <tr>
                        <td>{{$vinculo->name}}</td>
                        <td>{{$vinculo->alcunha}}</td>
                        <td>{{$vinculo->cpf}}</td>
                        <td>{{$vinculo->tipo_vinculo}}</td>
                        <td>{{$vinculo->orcrim}}</td>
                        <td>{{$vinculo->cargo}}</td>
                        <td>{{$vinculo->area_atuacao}}</td>
                        <td>{{$vinculo->matricula}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" name="vinculo_orcrims[]" value="{{json_encode($vinculo)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        let vinculoOrcrimIndex = 0;

        function addVinculoOrcrim() {
            const name = document.getElementById('vinculo_name').value;
            const alcunha = document.getElementById('vinculo_alcunha').value;
            const cpf = document.getElementById('vinculo_cpf').value;
            const tipoVinculo = document.getElementById('vinculo_tipo_vinculo').value;
            const orcrim = document.getElementById('vinculo_orcrim').value;
            const cargo = document.getElementById('vinculo_cargo').value;
            const areaAtuacao = document.getElementById('vinculo_area_atuacao').value;
            const matricula = document.getElementById('vinculo_matricula').value;

            if (name.trim() === '') {
                alert('O campo Nome é obrigatório.');
                return;
            }

            // Cria o objeto do vínculo
            const vinculoData = {
                name: name,
                alcunha: alcunha,
                cpf: cpf,
                tipo_vinculo: tipoVinculo,
                orcrim: orcrim,
                cargo: cargo,
                area_atuacao: areaAtuacao,
                matricula: matricula
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `vinculo_orcrims[${vinculoOrcrimIndex}]`);
            input.setAttribute('value', JSON.stringify(vinculoData));

            // Cria a linha na tabela
            let table = document.querySelector('#tableVinculoOrcrims');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${name}</td>
                <td>${alcunha}</td>
                <td>${cpf}</td>
                <td>${tipoVinculo}</td>
                <td>${orcrim}</td>
                <td>${cargo}</td>
                <td>${areaAtuacao}</td>
                <td>${matricula}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('vinculo_name').value = '';
            document.getElementById('vinculo_alcunha').value = '';
            document.getElementById('vinculo_cpf').value = '';
            document.getElementById('vinculo_tipo_vinculo').value = '';
            document.getElementById('vinculo_orcrim').value = '';
            document.getElementById('vinculo_cargo').value = '';
            document.getElementById('vinculo_area_atuacao').value = '';
            document.getElementById('vinculo_matricula').value = '';

            // Incrementa o índice
            vinculoOrcrimIndex++;
        }
    </script>
@endpush 