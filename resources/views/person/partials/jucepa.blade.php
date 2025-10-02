<div class="card">
    <div class="card-body">
        @php
            // Normalizar entrada: aceitar tanto $jucepa direto quanto estruturas com chaves
            $payload = $jucepa ?? [];

            $dadosCadastrais = [];
            $quadroSocietario = [];

            if (is_array($payload)) {
                // Possíveis chaves comuns
                if (array_key_exists('DadosCadastrais', $payload)) {
                    $dadosCadastrais = $payload['DadosCadastrais'];
                } elseif (array_key_exists('dadosCadastrais', $payload)) {
                    $dadosCadastrais = $payload['dadosCadastrais'];
                }

                if (array_key_exists('QuadroSocietario', $payload)) {
                    $quadroSocietario = $payload['QuadroSocietario'];
                } elseif (array_key_exists('quadroSocietario', $payload)) {
                    $quadroSocietario = $payload['quadroSocietario'];
                }

                // Caso o payload seja diretamente a lista de dados cadastrais (array indexado)
                if (empty($dadosCadastrais) && !empty($payload) && array_values($payload) === $payload) {
                    // Se o primeiro item tem chaves típicas de cadastro, assumir como dadosCadastrais
                    $first = $payload[0] ?? null;
                    if (is_array($first) && (array_key_exists('documento', $first) || array_key_exists('pessoa', $first))) {
                        $dadosCadastrais = $payload;
                    }
                }
            }

            // Garantir que dadosCadastrais seja array indexado
            if ($dadosCadastrais && !is_array($dadosCadastrais)) $dadosCadastrais = [$dadosCadastrais];
            if ($quadroSocietario && !is_array($quadroSocietario)) $quadroSocietario = [$quadroSocietario];
        @endphp

        {{-- Dados Cadastrais --}}
        <div class="mb-3">
            <h5>PGE - JUCEPA — Dados Cadastrais</h5>
            @if(empty($dadosCadastrais) || count($dadosCadastrais) === 0)
                <div class="alert alert-info">Nenhum registro de Dados Cadastrais encontrado para {{ $documento ?? '-' }}.</div>
            @else
                @php
                    // Usar apenas o primeiro registro como resumo principal
                    $dc = $dadosCadastrais[0];
                    $dc = is_array($dc) ? $dc : (is_object($dc) ? json_decode(json_encode($dc), true) : []);
                    $preferred = ['documento', 'pessoa', 'cep', 'cidade', 'endereco', 'email', 'telefone'];
                    $available = array_keys($dc);
                    $ordered = [];
                    foreach ($preferred as $p) {
                        $idx = array_search($p, $available);
                        if ($idx !== false) {
                            $ordered[] = $available[$idx];
                            unset($available[$idx]);
                        }
                    }
                    $remaining = array_values($available);
                    $displayKeys = array_merge($ordered, $remaining);
                @endphp

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-borderless">
                            <tbody>
                            @foreach($displayKeys as $k)
                                @if(in_array($k, $preferred) )
                                    <tr>
                                        <th style="width:200px">{{ ucwords(str_replace(['_','-'], [' ', ' '], $k)) }}</th>
                                        <td>{{ isset($dc[$k]) && $dc[$k] !== null && $dc[$k] !== '' ? $dc[$k] : '-' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Quadro Societário --}}
        <div>
            <h5>PGE - JUCEPA — Quadro Societário</h5>
            @if(empty($quadroSocietario) || count($quadroSocietario) === 0)
                <div class="alert alert-info">Nenhum registro de Quadro Societário encontrado para {{ $documento ?? '-' }}.</div>
            @else
                @php
                    // Normalizar cada item para array
                    $rows = [];
                    foreach ($quadroSocietario as $r) {
                        $rows[] = is_array($r) ? $r : (is_object($r) ? json_decode(json_encode($r), true) : []);
                    }

                    // Coletar todas as chaves
                    $allKeys = [];
                    foreach ($rows as $row) {
                        foreach (array_keys($row) as $k) {
                            if (!in_array($k, $allKeys)) $allKeys[] = $k;
                        }
                    }

                    // Chaves preferenciais para exibição
                    $preferred = ['dt_entrada_sociedade', 'dt_saida_sociedade', 'no_pessoa', 'no_vinculo', 'nr_cgc', 'nr_docsocio', 'razaosocial', 'statussociedade'];
                    $ordered = [];
                    foreach ($preferred as $p) {
                        $idx = array_search($p, $allKeys);
                        if ($idx !== false) {
                            $ordered[] = $allKeys[$idx];
                            unset($allKeys[$idx]);
                        }
                    }
                    $remaining = array_values($allKeys);
                    $allKeys = array_merge($ordered, $remaining);

                    // Estatísticas simples
                    $total = count($rows);
                    $active = 0; $inactive = 0;
                    foreach ($rows as $rr) {
                        $status = $rr['statussociedade'] ?? ($rr['status'] ?? null);
                        if ($status) {
                            $s = mb_strtolower(trim((string)$status));
                            // Remover acentos para comparação mais robusta
                            try { $s = \Illuminate\Support\Str::ascii($s); } catch (\Throwable $e) { /* ignore */ }
                            // Verificar inativo primeiro (contém 'inativ'), depois ativo
                            if (str_contains($s, 'inativ')) {
                                $inactive++;
                            } elseif (str_contains($s, 'ativo')) {
                                $active++;
                            }
                        }
                    }
                @endphp

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Total participações: {{ $total }} — Ativas: {{ $active }} — Inativas: {{ $inactive }}</small>
                    <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" data-target="#jucepa-quadro" aria-expanded="false">Mostrar/Ocultar Quadro Societário</button>
                </div>

                <div class="collapse show" id="jucepa-quadro">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                @foreach($allKeys as $k)
                                    <th>{{ ucwords(str_replace(['_','-'], [' ', ' '], $k)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $item)
                            <tr>
                                @foreach($allKeys as $k)
                                    @php
                                        $cell = is_array($item) && array_key_exists($k, $item) ? $item[$k] : null;
                                        // Formatar datas simples no padrão dd/mm/YYYY quando possível
                                        if ($cell && ($k === 'dt_entrada_sociedade' || $k === 'dt_saida_sociedade')) {
                                            try {
                                                $d = \Carbon\Carbon::createFromFormat('d/m/Y', $cell);
                                                $cell = $d->format('d/m/Y');
                                            } catch (\Throwable $e) {
                                                // se já estiver em outro formato, tentar parse genérico
                                                try { $d = \Carbon\Carbon::parse($cell); $cell = $d->format('d/m/Y'); } catch (\Throwable $e) { }
                                            }
                                        }
                                    @endphp
                                    <td style="vertical-align: top; white-space: pre-wrap; max-width: 300px;">
                                        @if(is_array($cell) || is_object($cell))
                                            <pre style="margin:0">{{ json_encode($cell, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @else
                                            @if($cell === null || $cell === '')
                                                -
                                            @else
                                                <span title="{{ is_array($cell) || is_object($cell) ? json_encode($cell, JSON_UNESCAPED_UNICODE) : $cell }}">{{ strlen((string)$cell) > 120 ? substr((string)$cell,0,117) . '...' : $cell }}</span>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            @endif
        </div>
    </div>
</div>
