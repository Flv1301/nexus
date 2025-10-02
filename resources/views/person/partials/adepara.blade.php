<div class="card">
    <div class="card-body">
        @if(empty($adepara) || count($adepara) === 0)
            <div class="alert alert-info">Nenhum registro ADEPARA encontrado para {{ $documento }}.</div>
        @else
            @php
                // Coletar todas as chaves presentes nos itens ADEPARA (normaliza objects)
                $allKeys = [];
                foreach ($adepara as $row) {
                    $rowArr = is_array($row) ? $row : (is_object($row) ? json_decode(json_encode($row), true) : []);
                    foreach (array_keys($rowArr) as $k) {
                        if (!in_array($k, $allKeys)) $allKeys[] = $k;
                    }
                }

                // Chaves preferenciais para ordenação
                $preferred = ['docraiz', 'docprodutor', 'municipio', 'nomeprodutor', 'nomepropriedade', 'tpdoc'];
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
            @endphp

            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">Total registros: {{ count($adepara) }}</small>
            </div>
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
                    @foreach($adepara as $itemRaw)
                        @php
                            // Normalizar item para array (aceitar stdClass/object)
                            $item = is_array($itemRaw) ? $itemRaw : (is_object($itemRaw) ? json_decode(json_encode($itemRaw), true) : []);
                        @endphp
                        <tr>
                            @foreach($allKeys as $k)
                                <td style="vertical-align: top; white-space: pre-wrap; max-width: 300px;">
                                    @if(is_array($item) && array_key_exists($k, $item))
                                        @php $val = $item[$k]; @endphp
                                        @if(is_array($val) || is_object($val))
                                            <pre style="margin:0">{{ json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @else
                                            {{ $val === null || $val === '' ? '-' : $val }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
