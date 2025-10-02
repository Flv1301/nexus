@if($person->active_orcrim && !auth()->user()->can('sisfac'))
    @include('sisfac_block')
@else
    <div class="card">
        <div class="card-header text-info">Empresas</div>
        <div class="card-body">
            @if($person->companies && $person->companies->count() > 0)
                <table class="table table-responsive col-md-12">
                    <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Nome de Fantasia</th>
                        <th>CNPJ</th>
                        <th>Telefone</th>
                        <th>Capital Social</th>
                        <th>Situação</th>
                        <th>CEP</th>
                        <th>Endereço</th>
                        <th>Número</th>
                        <th>Bairro</th>
                        <th>UF</th>
                        <th>Cidade</th>
                        <th>CNAE</th>
                        <th>Contador</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($person->companies as $company)
                        <tr>
                            <td>{{ $company->company_name ?? '-' }}</td>
                            <td>{{ $company->fantasy_name ?? '-' }}</td>
                            <td>{{ $company->cnpj ?? '-' }}</td>
                            <td>{{ $company->phone ?? '-' }}</td>
                            <td>{{ $company->social_capital ?? '-' }}</td>
                            <td>{{ $company->status ?? '-' }}</td>
                            <td>{{ $company->cep ?? '-' }}</td>
                            <td>{{ $company->address ?? '-' }}</td>
                            <td>{{ $company->number ?? '-' }}</td>
                            <td>{{ $company->district ?? '-' }}</td>
                            <td>{{ $company->uf ?? '-' }}</td>
                            <td>{{ $company->city ?? '-' }}</td>
                            <td>{{ $company->cnae ?? '-' }}</td>
                            <td>{{ $company->accountant ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nenhuma empresa cadastrada para esta pessoa.
                </div>
            @endif
        </div>
    </div>
@endif 