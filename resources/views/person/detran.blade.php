@extends('adminlte::page')

@section('title', 'PGE - DETRAN')

@section('content_header')
    <h1 class="m-0 text-dark">PGE - DETRAN: {{ $documento }}</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @if(empty($detran) || count($detran) === 0)
                <div class="alert alert-info">Nenhum registro DETRAN encontrado para {{ $documento }}.</div>
            @else
                @php
                    // Collect all keys present in the DETRAN items
                    $allKeys = [];
                    foreach ($detran as $row) {
                        if (is_array($row)) {
                            foreach (array_keys($row) as $k) {
                                if (!in_array($k, $allKeys)) $allKeys[] = $k;
                            }
                        }
                    }

                    // Prefer common keys first
                    $preferred = ['placa', 'proprietario', 'renavam', 'marcamodelo', 'marca_modelo', 'ano', 'anofabricacao', 'anomodelo', 'procedencia'];
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
                        @foreach($detran as $item)
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

            {{-- payload cru removido para exibição limpa --}}
        </div>
    </div>
@endsection
