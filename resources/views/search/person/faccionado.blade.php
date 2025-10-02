<div class="card">
    <div class="card-header">
        <h3 class="card-title">FACCIONADO</h3>
        <div class="card-tools">
            <span class="badge badge-danger">Pessoas com Status Ativo</span>
        </div>
    </div>
    <div class="card-body">
        @if(isset($bases) && isset($bases['faccionado']) && $bases['faccionado']->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Mãe</th>
                            <th>Nascimento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bases['faccionado'] as $person)
                        <tr>
                            <td>{{ $person->name ?? '' }}</td>
                            <td>{{ $person->cpf ?? '' }}</td>
                            <td>{{ $person->mother ?? '' }}</td>
                            <td>{{ $person->birth_date ?? '' }}</td>
                            <td>
                                <a href="{{ route('person.show', $person->id) }}"
                                   class="btn btn-sm btn-primary"
                                   title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('person.search.report', $person->id) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-danger"
                                   title="Relatório PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center p-4">
                <i class="fas fa-info-circle fa-2x text-muted"></i>
                <p class="mt-2 text-muted">
                    @if(isset($bases))
                        Nenhuma pessoa faccionada encontrada com os critérios de busca informados.
                    @else
                        Execute uma busca para visualizar pessoas faccionadas.
                    @endif
                </p>
            </div>
        @endif
    </div>
    <div class="card-footer">
        <small class="text-muted">
            <i class="fas fa-info-circle"></i>
            Esta aba exibe apenas pessoas que possuem o campo "Ativo" marcado como SIM na aba Dados.
            @if(isset($bases) && isset($bases['faccionado']))
                <strong>{{ $bases['faccionado']->count() }} resultado(s) encontrado(s).</strong>
            @endif
        </small>
    </div>
</div>
