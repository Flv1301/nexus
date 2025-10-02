<div class="card col-md-12">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>CAC</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Calibre</th>
                <th>SINARM</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody id="tableArmas">
            @if(isset($person) && $person->armas->count())
                @foreach($person->armas as $arma)
                    <tr>
                        <td>{{$arma->cac}}</td>
                        <td>{{$arma->marca}}</td>
                        <td>{{$arma->modelo}}</td>
                        <td>{{$arma->calibre}}</td>
                        <td>{{$arma->sinarm}}</td>
                        <td><i class="fa fa-md fa-fw fa-trash text-danger"
                               onclick="$(this).parent().parent().remove()"
                               title="Remover"></i></td>
                        <input type="hidden" name="armas[]" value="{{json_encode($arma)}}">
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>

@push('js')
    <script>
        let armaIndex = {{ isset($person) && $person->armas ? $person->armas->count() : 0 }};

        function addArma() {
            const cac = document.getElementById('arma_cac').value;
            const marca = document.getElementById('arma_marca').value;
            const modelo = document.getElementById('arma_modelo').value;
            const calibre = document.getElementById('arma_calibre').value;
            const sinarm = document.getElementById('arma_sinarm').value;

            if (cac.trim() === '' && marca.trim() === '' && modelo.trim() === '' && calibre.trim() === '' && sinarm.trim() === '') {
                alert('Pelo menos um campo deve ser preenchido.');
                return;
            }

            // Cria o objeto da Arma
            const armaData = {
                cac: cac,
                marca: marca,
                modelo: modelo,
                calibre: calibre,
                sinarm: sinarm
            };

            // Cria input hidden para enviar os dados
            let input = document.createElement('input');
            input.setAttribute('type', 'hidden');
            input.setAttribute('name', `armas[${armaIndex}]`);
            input.setAttribute('value', JSON.stringify(armaData));

            // Cria a linha na tabela
            let table = document.querySelector('#tableArmas');
            let tr = document.createElement('tr');
            
            tr.innerHTML = `
                <td>${cac}</td>
                <td>${marca}</td>
                <td>${modelo}</td>
                <td>${calibre}</td>
                <td>${sinarm}</td>
                <td><i class="fa fa-md fa-fw fa-trash text-danger" onclick="$(this).parent().parent().remove()" title="Remover"></i></td>
            `;
            
            tr.appendChild(input);
            table.appendChild(tr);

            // Limpa os campos
            document.getElementById('arma_cac').value = '';
            document.getElementById('arma_marca').value = '';
            document.getElementById('arma_modelo').value = '';
            document.getElementById('arma_calibre').value = '';
            document.getElementById('arma_sinarm').value = '';

            // Incrementa o índice
            armaIndex++;
        }
    </script>
@endpush 