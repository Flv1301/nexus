@extends('adminlte::page')
@section('title', 'Qualificação Completa')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('content_header')
    <x-page-header title="Pesquisa Avançada">
        <div>
            <a href="{{ url()->previous() }}" id="history" class="btn btn-info" type="button"><i
                    class="fas fa-sm fa-backward p-1"></i>Voltar</a>
        </div>
    </x-page-header>
@endsection
@section('content')
    <div class="card">
        <x-loading/>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                @can('seap')
                                    <li class="nav-item"><a
                                            class="nav-link border border-secondary rounded font-weight-bold"
                                            href="#" data-toggle="tab" onclick="selectBase('seap')">SEAP</a>
                                    </li>
                                @endcan
                                @can('sisp')
                                    <li class="nav-item"><a
                                            class="nav-link ml-3 border border-secondary rounded font-weight-bold"
                                            href="#" data-toggle="tab" onclick="selectBase('sisp')">SISP</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <form action="{{ route('search.advanced.search') }}" method="get" id="form-search">
                                        @csrf
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="table-search">
                                                <thead>
                                                <tr>
                                                    <th style="width: 100px;" class="text-truncate">E/OU</th>
                                                    <th style="width: 100px" class="text-truncate">Agrupar</th>
                                                    <th class="text-truncate">Campo</th>
                                                    <th style="width: 200px;" class="text-truncate">Operador</th>
                                                    <th class="text-truncate">Valor</th>
                                                    <th style="width: 60px;" class="text-truncate">Ação</th>
                                                </tr>
                                                </thead>
                                                <tbody id="table-tbody">
                                                <tr>
                                                    <td class="align-middle">
                                                        <select class="form-control" name="logicals[]">
                                                            <option value="AND">E</option>
                                                            <option value="OR">OU</option>
                                                        </select>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <select class="form-control" name="logicalgroup[]">
                                                            <option value="0">Não</option>
                                                            <option value="1">Sim</option>
                                                        </select>
                                                    </td>
                                                    <td id="column-select" class="align-middle">
                                                        <select class="form-control column-select" name="columns[]"
                                                                onchange="placeholder(this)">
                                                        </select>
                                                    </td>
                                                    <td class="align-middle">
                                                        <select name="operators[]" class="form-control">
                                                            <option value="like">Contém</option>
                                                            <option value="not like">Não Contém</option>
                                                            <option value="=">Igual</option>
                                                            <option value="<>">Diferente</option>
                                                            <option value=">">Maior</option>
                                                            <option value="<">Menor</option>
                                                            <option value=">=">Maior ou Igual</option>
                                                            <option value="<=">Menor ou Igual</option>
                                                        </select>
                                                    </td>
                                                    <td class="align-middle"><input id="values" name="values[]"
                                                                                    type="text"
                                                                                    class="form-control text-uppercase"
                                                                                    placeholder=""/></td>
                                                    <td class="align-middle"><a onclick="removeRow(this)"
                                                                                class="btn btn-sm btn-danger"><i
                                                                class="fa fa-trash-alt"></i></a></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <input type="hidden" id="base" name="base">
                                        <div class="d-flex justify-content-end">
                                            <x-adminlte-button theme="success" icon="fa fa-plus" class="mr-2"
                                                               id="btn-plus" onclick="appendRow()"/>
                                        </div>
                                        <div class="container-sm">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-sm mt-2">
                                                    <thead>
                                                    <tr>
                                                        <th class="col-3">Campo de Ordenação</th>
                                                        <th class="col-1 text-center">Decrescente</th>
                                                        <th class="col-1 text-center">Paginação</th>
                                                        <th class="col-1 text-center">Limite</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <td class="text-center">
                                                            <select class="form-control form-control-sm" name="orderBy"
                                                                    id="orderBy"></select>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input type="checkbox" id="orderByDesc" value="1"
                                                                       name="orderByDesc" class="form-check-input"
                                                                       style="transform: scale(1.2);"/>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select class="form-control form-control-sm" name="paginate"
                                                                    id="paginate">
                                                                <option value="15">15</option>
                                                                <option value="25">25</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <x-adminlte-input name="limit" type="number" min="1" id="limit"/>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div>
                                                <x-adminlte-button label="Pesquisar" theme="primary" icon="fa fa-search"
                                                                   id="btn-search" type="submit"/>
                                            </div>
                                        </div>
                                        <span class="text-info mt-2">* Selecione o Banco De Dados Para Pesquisar</span>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body" id="result-content"></div>
    </div>
    <div id="modal"></div>
@endsection
@push('js')
    <script>
        const loading = (active) => {
            const loading = document.getElementById('loading');
            loading.classList.toggle('d-none', !active);
        };

        let data = {};
        let baseSelected = '';
        const btnPlus = document.querySelector('#btn-plus');
        const btnSearch = document.querySelector('#btn-search');

        btnPlus.disabled = true;
        btnSearch.disabled = true;

        async function selectBase(base) {
            if (baseSelected !== base) {
                clearOptions();
            }
            loading(true);
            baseSelected = base;
            document.querySelector('#base').value = base;

            try {
                const response = await fetch(`{{ route('search.bases.columns') }}?b=${base}`);
                if (!response.ok) {
                    throw new Error('Erro ao obter os dados da base!');
                }
                data = await response.json();
                const databaseSelect = document.querySelector('.column-select');
                const orderBy = document.querySelector('#orderBy');
                orderBy.innerHTML = '<option />';
                databaseSelect.innerHTML = '<option />';

                for (const column in data) {
                    if (data.hasOwnProperty(column)) {
                        const option = document.createElement('option');
                        option.value = column;
                        option.textContent = data[column][0];
                        databaseSelect.appendChild(option);
                        const orderByOption = option.cloneNode(true);
                        orderBy.appendChild(orderByOption);
                    }
                }
                btnPlus.disabled = false;
                btnSearch.disabled = false;
            } catch (error) {
                console.error(error);
            } finally {
                loading(false);
            }
        }

        function placeholder(select) {
            const field_search = select.closest('tr').querySelector('input[name="values[]"]');
            field_search.placeholder = data[select.value][1] || '';
        }

        function appendRow() {
            const fields = document.getElementById('table-tbody');
            const clone = fields.querySelector('tr').cloneNode(true);
            const inputValue = clone.querySelector('#values');
            inputValue.value = '';
            inputValue.id = Math.random();
            inputValue.placeholder = '';
            fields.appendChild(clone);
        }

        function removeRow(button) {
            const row = button.closest('tr');
            const tableBody = row.parentNode;

            if (tableBody.children.length > 1) {
                tableBody.removeChild(row);
            } else {
                row.querySelector('select[name="logicals[]"]').value = 'AND';
                row.querySelector('.column-select').selectedIndex = 0;
                row.querySelector('select[name="operators[]"]').selectedIndex = 0;
                row.querySelector('input[name="values[]"]').value = '';
                const groupCheckbox = row.querySelector('input[type="checkbox"]');
                if (groupCheckbox) {
                    groupCheckbox.checked = false;
                }
            }
        }

        function clearOptions() {
            const table = document.getElementById('table-search');
            const tbody = table.querySelector('tbody');

            while (tbody.children.length > 1) {
                tbody.removeChild(tbody.lastChild);
            }
        }

        const form = document.getElementById('form-search');
        const action = form.getAttribute('action');
        const resultContent = document.getElementById('result-content');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            resultContent.innerHTML = '';
            loading(true);
            const formData = new FormData(form);
            const params = new URLSearchParams(formData).toString();
            const apiUrl = action + '?' + params;

            try {
                const response = await fetch(apiUrl, {
                    method: 'GET',
                });
                if (!response.ok) {
                    alert('Erro na obtenção dos dados!');
                }
                const data = await response.json();
                resultContent.innerHTML = data.html;
                clickLink();
                paginate();
            } catch (error) {
                console.error('Erro:', error);
            } finally {
                loading(false);
            }
        });

        function clickLink() {
            const openModalLinks = document.querySelectorAll('.open-modal-link');
            openModalLinks.forEach(link => {
                link.addEventListener('click', async (event) => {
                    event.preventDefault();
                    const url = link.getAttribute('href');
                    openModal(url);
                });
            });
        }

        function paginate() {
            const paginateLinks = document.querySelectorAll('.page-link');
            paginateLinks.forEach(link => {
                link.addEventListener('click', async (event) => {
                    event.preventDefault();
                    loading(true);
                    resultContent.innerHTML = '';
                    const url = link.getAttribute('href');

                    try {
                        const response = await fetch(url, {
                            method: 'GET',
                        });
                        if (!response.ok) {
                            alert('Erro na obtenção dos dados!');
                        }
                        const data = await response.json();
                        resultContent.innerHTML = data.html;
                        clickLink();
                        paginate();
                    } catch (error) {
                        console.error('Erro:', error);
                    } finally {
                        loading(false);
                    }
                });
            });
        }

        function openModal(url) {
            loading(true);
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('O Sistema não pode atender à solicitação!');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('modal').innerHTML = data.html;
                    const modal = document.getElementById('base-modal');
                    $(modal).modal('show');
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    loading(false);
                });
        }
    </script>
@endpush
