<div class="card-body">
    <div class="tab-content">
        <div class="tab-pane active" id="tab_data" role="tabpanel">
            @include('person.data')
        </div>
        <div class="tab-pane" id="tab_address" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    @include('address.form')
                </div>
                <div class="card-footer">
                    <x-adminlte-button
                        type="button"
                        icon="fas fa-plus"
                        theme="secondary"
                        label="Adicionar"
                        onclick="addAddress()"
                    />
                </div>
            </div>
            <div class="card col-md-12">
                <div class="card-body">
                    <table class="table table-responsive col-md-12">
                        <thead>
                        <tr>
                            <th>CEP</th>
                            <th>Endereço</th>
                            <th>Número</th>
                            <th>Bairro</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th>UF</th>
                            <th>Observação</th>
                        </tr>
                        </thead>
                        <tbody id="tableAddress">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
