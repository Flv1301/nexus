@props(['bases', 'route'])

@php
    $heads = [
        'Base',
        'Nome',
        'CPF',
        'Mãe',
        ['label' => 'Opções', 'no-export' => true, 'width' => 15],
    ];
    $config = [
        'order' => [[1, 'asc']],
        'columns' => [null, null, null, null, ['orderable' => false]],
        'language' => [
                        'paginate' => [
                            'first' => 'Primeiro',
                            'last' => 'Último',
                            'next' => 'Próximo',
                            'previous' => 'Anterior',
                        ],
                        'search' => 'Pesquisar na Tabela',
                        'lengthMenu'=>    "Mostrar  _MENU_  Resultados",
                        'info'=>           "Mostrando _START_ a _END_ de _TOTAL_ Resultados.",
                        'infoEmpty'=>      "Mostrando 0 Resultados.",
                        'infoFiltered'=>   "(Filtro de _MAX_ Resultados no total)",
                        'loadingRecords'=> "Pesquisando...",
                        'zeroRecords'=>    "Nem um dado(s) encontrado(s)",
                        'emptyTable'=>     "Sem dados!",
                    ],
    ];
@endphp
<div class="card">
    <div class="card-body">
        <!-- Debug: chaves das bases em atributo data para facilitar inspeção no browser -->
        <div id="bases-keys" data-keys='<?php echo json_encode(array_keys((array)($bases ?? []))); ?>' style="display:none"></div>
        <div id="persons-search-container">
            <x-adminlte-datatable id="tbl_persons_search" :heads="$heads" :config="$config" striped hoverable>
            @foreach($bases as $key => $base)
                @if($base->count() > 0)
                    @foreach($base as $person)
                        <tr>
                            <td>{{ $key === 'person' ? 'HYDRA' : \Illuminate\Support\Str::upper($key)}}</td>
                            <td>{{$person->name}}</td>
                            <td>{{$person->cpf ?? ''}}</td>
                            <td>{{$person->mother ?? ''}}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($key === 'faccionado')
                                        {{-- Para dados faccionados, redireciona para visualização normal da pessoa --}}
                                        <a href="{{route('person.show', $person->id)}}" class="mr-2" title="Visualizar Dados">
                                            <i class="fas fa-lg fa-eye text-primary"></i>
                                        </a>
                                    @else
                                        {{-- Para outras bases, usa a rota de busca normal --}}
                                                @if(Str::startsWith($key, 'pge_'))
                                                    {{-- Suporta ADEPARA e SEMAS além do DETRAN (compatibilidade padrão) --}}
                                                    @php
                                                        $routeUrl = route('pge.detran', ['documento' => $person->cpf ?? $person->id ?? '']);
                                                        if (Str::contains($key, 'adepara')) {
                                                            $routeUrl = route('pge.adepara', ['documento' => $person->cpf ?? $person->id ?? '']);
                                                        } elseif (Str::contains($key, 'semas')) {
                                                            $routeUrl = route('pge.semas', ['documento' => $person->cpf ?? $person->id ?? '']);
                                                        } elseif (Str::contains($key, 'jucepa')) {
                                                            $routeUrl = route('pge.jucepa', ['documento' => $person->cpf ?? $person->id ?? '']);
                                                        }
                                                    @endphp
                                                    <a href="#" class="mr-2 pge-load" data-url="{{ $routeUrl }}" data-documento="{{ $person->cpf ?? $person->id ?? '' }}" title="Detalhes PGE">
                                                        <i class="fas fa-lg fa-eye text-primary"></i>
                                                    </a>
                                        @else
                                            <a href="{{route($route, ['base'=> $key, 'id' => $person->id])}}" class="mr-2" title="Visualizar Dados">
                                                <i class="fas fa-lg fa-eye text-primary"></i>
                                            </a>
                                        @endif
                                    @endif
                                    @if($key === 'person' || $key === 'nexus' || $key === 'faccionado')
                                    <a href="{{route('person.search.report', ['id' => $person->id])}}" title="Gerar Relatório PDF" target="_blank">
                                        <i class="fas fa-lg fa-file-pdf text-danger"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </x-adminlte-datatable>
    </div>
</div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                        try {
                            const el = document.getElementById('bases-keys');
                            if (el) {
                                const keys = JSON.parse(el.getAttribute('data-keys') || '[]');
                                console.debug('show-bases-persons: bases keys (from DOM):', keys);
                            }
                        } catch (e) {
                            console.debug('show-bases-persons: failed to parse bases keys', e);
                        }
                try {
                    // Debug: registrar chaves das bases recebidas do servidor
                    // `bases` é passado como prop ao componente; imprimir nomes de chave ajuda no diagnóstico
                    console.debug('show-bases-persons: bases keys:', {!! json_encode(array_keys((array)($bases ?? []))) !!});
                } catch (e) {
                    console.debug('show-bases-persons: debug error', e);
                }
                // Registra handler de popstate para restaurar conteúdo anterior
                window.addEventListener('popstate', function (ev) {
                    const container = document.getElementById('content-result') || document.getElementById('persons-search-container');
                    if (!container) return;
                    if (ev.state && ev.state.html) {
                        container.innerHTML = ev.state.html;
                    }
                });

                // Handler para carregar partial via AJAX com overlay/spinner e history
                document.querySelectorAll('.pge-load').forEach(function (el) {
                    el.addEventListener('click', function (ev) {
                        ev.preventDefault();
                        const url = el.getAttribute('data-url');
                        // Debug: log dos atributos do link
                        console.debug('pge-load click:', { url: url, dataDocumento: el.getAttribute('data-documento'), elDataset: el.dataset });
                        if (!url) return;

                        // Prioriza injetar o conteúdo na div principal de resultados (#content-result)
                        // Caso não exista (uso do componente isolado), usa o container local.
                        const container = document.getElementById('content-result') || document.getElementById('persons-search-container');
                        if (!container) return;

                        // Criar e inserir overlay/spinner temporário
                        const overlay = document.createElement('div');
                        overlay.className = 'pge-overlay d-flex align-items-center justify-content-center';
                        overlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Carregando...</span></div>';
                        // salvar conteúdo anterior para fallback/popstate
                        const previousContent = container.innerHTML;
                        container.innerHTML = '';
                        container.appendChild(overlay);

                        fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' },
                            credentials: 'same-origin'
                        }).then(function (resp) {
                            if (!resp.ok) throw new Error('Network response was not ok');
                            return resp.text();
                        }).then(function (html) {
                            // Debug: mostrar preview do HTML recebido
                            console.debug('pge-load: resposta HTML recebida (primeiros 200 chars):', html ? html.substring(0, 200) : html);
                            // Ao invés de empurrar a URL /pge/... no history, empurra uma URL representativa da tela de pesquisa
                            var documento = el.getAttribute('data-documento') || '';
                            // Determinar a base a partir da URL (se contém 'semas' ou 'adepara' ou 'detran')
                            var base = 'detran';
                            if (url.indexOf('/pge/adepara') !== -1) base = 'adepara';
                            if (url.indexOf('/pge/semas') !== -1) base = 'semas';
                            if (url.indexOf('/pge/jucepa') !== -1) base = 'jucepa';
                            var searchUrl = '/pesquisa/pessoa?view_base=' + encodeURIComponent(base) + '&documento=' + encodeURIComponent(documento);
                            try {
                                history.pushState({ html: previousContent }, '', searchUrl);
                            } catch (e) {
                                console.warn('pushState falhou', e);
                            }
                            container.innerHTML = html;
                            console.debug('pge-load: container atualizado para base', base, 'documento', documento);
                        }).catch(function (err) {
                            console.error('Erro ao carregar PGE:', err);
                            alert('Erro ao carregar dados PGE');
                            // restaurar conteúdo anterior
                            container.innerHTML = previousContent;
                        });
                    });
                });
            });
        </script>
    @endpush

    @push('css')
        <style>
            .pge-spinner { min-height: 120px; }
            .pge-overlay {
                min-height: 160px;
                background: rgba(255,255,255,0.9);
                animation: fadeIn 180ms ease-in-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(6px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    @endpush
