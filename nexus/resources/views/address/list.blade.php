<div class="card">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>CEP</th>
                <th>Endereço</th>
                <th>Número</th>
                <th>Bairro</th>
                <th>Cidade</th>
                <th>UF</th>
                <th>Complemento</th>
                <th>Ponto de Referencia</th>
                <th>Observação</th>
                <th>Data do dado</th>
                <th>Fonte do dado</th>
            </tr>
            </thead>
            <tbody id="tableAddress">
            @foreach($person->address as $address)
                <tr>
                    <td>{{$address->code}}</td>
                    <td>{{$address->address}}</td>
                    <td>{{$address->number}}</td>
                    <td>{{$address->district}}</td>
                    <td>{{$address->city}}</td>
                    <td>{{$address->uf}}</td>
                    <td>{{$address->complement}}</td>
                    <td>{{$address->reference_point}}</td>
                    <td>{{$address->observacao}}</td>
                    <td>{{$address->data_do_dado ? date('d/m/Y', strtotime($address->data_do_dado)) : ''}}</td>
                    <td>{{$address->fonte_do_dado}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
