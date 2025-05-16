<div class="card">
    <div class="card-body">
        <table class="table table-responsive col-md-12">
            <thead>
            <tr>
                <th>DDD</th>
                <th>Telefone</th>
                <th>Proprietário</th>
                <th>Operadora</th>
                <th>Data de Ativação</th>
                <th>Data de Cancelamento</th>
                <th>IMEI</th>
                <th>IMSI</th>
                <th>Modelo do Aparelho</th>
            </tr>
            </thead>
            <tbody id="tableContacts">
            @foreach($person->telephones as $telephones)
                <tr>
                    <td>{{$telephones->ddd}}</td>
                    <td>{{$telephones->telephone}}</td>
                    <td>{{$telephones->owner}}</td>
                    <td>{{$telephones->operator}}</td>
                    <td>{{$telephones->start_link}}</td>
                    <td>{{$telephones->end_link}}</td>
                    <td>{{$telephones->imei}}</td>
                    <td>{{$telephones->imsi}}</td>
                    <td>{{$telephones->device}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

