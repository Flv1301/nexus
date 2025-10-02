<?php
// app/Http/Controllers/Pge/PgeController.php
namespace App\Http\Controllers\Pge;

use App\Http\Controllers\Controller;
use App\APIs\PgeApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Person\Person;

class PgeController extends Controller
{
    private PgeApi $client;
    // função construtora para injetar o PgeApi
    public function __construct(PgeApi $client)
    {
        $this->client = $client;
    }
    /**
     * Consulta o PGE por documento (CPF).
     * Se a requisição aceita HTML (browser), renderiza uma view com os dados.
     * Se a requisição é AJAX ou aceita JSON, retorna JSON com os dados.
     */
    public function consult(Request $request)
    {
        $data = $request->validate(['documento' => 'required|string']);

        try {
            // Normaliza documento para chave de cache (apenas dígitos)
            $documentoOnlyDigits = preg_replace('/\D/', '', $data['documento']);
            $cacheKey = 'pge_consult_' . $documentoOnlyDigits;

            // Cache por 5 minutos para reduzir chamadas externas
            $resp = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($data) {
                return $this->client->get('consultamp', ['documento' => $data['documento']]);
            });

            // Se o client retornou um erro padronizado
            if (is_array($resp) && array_key_exists('error', $resp)) {
                $status = $resp['status'] ?? 500;
                Log::warning('PgeApi returned error', ['documento' => $data['documento'], 'resp' => $resp]);
                return response()->json(['error' => $resp['error']], $status);
            }

            // Se a requisição aceita HTML (browser), renderiza uma view com os dados
            if ($request->wantsJson() === false) {
                // Tenta extrair as fontes e especificamente DETRAN
                $fontes = [];
                if (is_array($resp)) {
                    if (array_key_exists('fontes', $resp)) $fontes = $resp['fontes'];
                    elseif (array_key_exists('data', $resp) && is_array($resp['data']) && array_key_exists('fontes', $resp['data'])) $fontes = $resp['data']['fontes'];
                }

                // Log debug: mostrar chaves/estrutura de `fontes` para diagnóstico
                try {
                    Log::info('PgeController::consult extracted fontes', [
                        'documento' => $data['documento'] ?? null,
                        'fontes_keys' => is_array($fontes) ? array_keys($fontes) : null,
                    ]);
                } catch (\Throwable $e) {
                    // Não interromper fluxo em caso de problema no log
                    Log::error('PgeController::consult log error', ['err' => $e->getMessage()]);
                }

                $detran = [];
                $adepara = [];
                $semas = [];
                $jucepa = [];
                foreach ($fontes as $k => $v) {
                    if (strtolower($k) === 'detran' && is_array($v)) {
                        $detran = $v;
                    }
                    if (strtolower($k) === 'adepara' && is_array($v)) {
                        $adepara = $v;
                    }
                    if (strtolower($k) === 'semas' && is_array($v)) {
                        $semas = $v;
                    }
                    if (strtolower($k) === 'jucepa' && is_array($v)) {
                        $jucepa = $v;
                    }
                }

                // Se não houver uma chave 'jucepa' explícita, agregue sub-blocos relevantes
                if (empty($jucepa)) {
                    $collected = [];
                    if (is_array($fontes)) {
                        foreach ($fontes as $k => $v) {
                            $keyL = strtolower($k);
                            if (in_array($keyL, ['adepara', 'semas', 'detran'])) continue;
                            if (str_contains($keyL, 'dados') || str_contains($keyL, 'quadro') || str_contains($keyL, 'jucepa')) {
                                if (is_iterable($v)) {
                                    $collected[$k] = is_array($v) ? $v : (array)$v;
                                } else {
                                    if (is_string($v)) {
                                        $decoded = json_decode($v, true);
                                        if (is_array($decoded)) $collected[$k] = $decoded;
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($collected)) {
                        $jucepa = $collected;
                        try {
                            Log::info('PgeController::consult aggregated jucepa from subkeys', [
                                'documento' => $data['documento'] ?? null,
                                'keys' => array_keys($collected),
                                'counts' => array_map('count', $collected),
                            ]);
                        } catch (\Throwable $e) {
                            // ignore
                        }
                    }
                }


                // Se ainda vazio, tentar recuperar a versão pré-gerada em cache feita pela tela de pesquisa
                if (empty($jucepa)) {
                    try {
                        $userId = auth()->id() ?? 'guest';
                        $cachedBases = cache()->get('person_search_' . $userId, null);
                        $entry = null;
                        if ($cachedBases) {
                            // aceitar array associativo ou Collection
                            if (is_array($cachedBases) && array_key_exists('pge_jucepa', $cachedBases)) {
                                $entry = $cachedBases['pge_jucepa'];
                            } elseif ($cachedBases instanceof \Illuminate\Support\Collection && $cachedBases->has('pge_jucepa')) {
                                $entry = $cachedBases->get('pge_jucepa');
                            }
                        }

                        // Se encontramos uma entrada, tentar extrair meta['jucepa'] do primeiro Person
                        if ($entry) {
                            if ($entry instanceof \Illuminate\Support\Collection) {
                                $first = $entry->first();
                            } elseif (is_array($entry)) {
                                $first = reset($entry);
                            } else {
                                $first = $entry;
                            }

                            if ($first && is_object($first) && isset($first->meta['jucepa'])) {
                                $jucepa = $first->meta['jucepa'];
                                Log::info('PgeController::consult used cached person_search jucepa', ['documento' => $data['documento'] ?? null]);
                            }
                        }
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
                // Log debug do conteúdo específico JUCEPA (pode ser grande - é só debug)
                try {
                    Log::info('PgeController::consult jucepa payload', [
                        'documento' => $data['documento'] ?? null,
                        'jucepa_keys' => is_array($jucepa) ? array_keys($jucepa) : null,
                    ]);
                } catch (\Throwable $e) {
                    Log::error('PgeController::consult jucepa log error', ['err' => $e->getMessage()]);
                }

                // Normalizar jucepa para garantir chaves previsíveis para a view
                if (is_array($jucepa)) {
                    $normalized = ['DadosCadastrais' => [], 'QuadroSocietario' => []];
                    if (array_key_exists('DadosCadastrais', $jucepa) || array_key_exists('dadosCadastrais', $jucepa)) {
                        $normalized['DadosCadastrais'] = array_values($jucepa['DadosCadastrais'] ?? $jucepa['dadosCadastrais'] ?? []);
                    }
                    if (array_key_exists('QuadroSocietario', $jucepa) || array_key_exists('quadroSocietario', $jucepa)) {
                        $normalized['QuadroSocietario'] = array_values($jucepa['QuadroSocietario'] ?? $jucepa['quadroSocietario'] ?? []);
                    }

                    // Se $jucepa for indexado e com campos de dados, usar como DadosCadastrais
                    if (empty($normalized['DadosCadastrais']) && array_values($jucepa) === $jucepa) {
                        $first = $jucepa[0] ?? null;
                        if (is_array($first) && (array_key_exists('documento', $first) || array_key_exists('pessoa', $first))) {
                            $normalized['DadosCadastrais'] = array_values($jucepa);
                        }
                    }

                    // preencher a partir de outras chaves quando possível
                    foreach ($jucepa as $k => $v) {
                        $lk = strtolower($k);
                        if ((str_contains($lk, 'dados') || str_contains($lk, 'cadast')) && empty($normalized['DadosCadastrais'])) {
                            $normalized['DadosCadastrais'] = is_iterable($v) ? array_values(is_array($v) ? $v : (array)$v) : [];
                        }
                        if ((str_contains($lk, 'quadro') || str_contains($lk, 'societ')) && empty($normalized['QuadroSocietario'])) {
                            $normalized['QuadroSocietario'] = is_iterable($v) ? array_values(is_array($v) ? $v : (array)$v) : [];
                        }
                    }

                    $jucepa = $normalized;
                }

                // Tenta localizar pessoa no banco pelo CPF (normalize apenas dígitos)
                $documentoOnlyDigits = preg_replace('/\D/', '', $data['documento']);
                $person = Person::where('cpf', $documentoOnlyDigits)->first();

                if (!$person) {
                    // cria um Person temporário para permitir renderizar o layout sem erros
                    $person = new Person();
                    $person->id = null;
                    $person->name = 'PGE - ' . $documentoOnlyDigits;
                    $person->cpf = $documentoOnlyDigits;
                }

                // Se a rota acessada for uma rota PGE específica, retornar o partial correspondente
                $routeName = $request->route() ? $request->route()->getName() : null;
                if ($routeName === 'pge.semas') {
                    // Se for AJAX, retornar apenas o partial
                    if ($request->ajax()) {
                        return view('person.partials.semas', ['semas' => $semas, 'documento' => $data['documento']]);
                    }

                    // Se acesso direto via navegador, renderizar a página de pesquisa com a base `pge_semas`
                    $personTmp = new Person();
                    $personTmp->id = 'pge_semas_' . ($resp['documento_consultado'] ?? $data['documento']);
                    $personTmp->name = 'PGE - SEMAS: ' . ($resp['documento_consultado'] ?? $data['documento']);
                    $personTmp->cpf = $resp['documento_consultado'] ?? $data['documento'];
                    $personTmp->mother = '';
                    $personTmp->meta = ['semas' => $semas];

                    // Salvar na sessão os dados de busca para que a tela de pesquisa carregue com as opções
                    session()->put('request_search', ['options' => ['pge_semas'], 'cpf' => $data['documento']]);
                    // Salvar bases em cache por 1 minuto associadas ao usuário para que a lista seja exibida
                    $userId = auth()->id() ?? 'guest';
                    cache()->put('person_search_' . $userId, ['pge_semas' => collect([$personTmp])], now()->addMinute(1));

                    return redirect()->route('person.search.index');
                }

                if ($routeName === 'pge.jucepa') {
                    if ($request->ajax()) {
                        return view('person.partials.jucepa', ['jucepa' => $jucepa, 'documento' => $data['documento']]);
                    }

                    $personTmp = new Person();
                    $personTmp->id = 'pge_jucepa_' . ($resp['documento_consultado'] ?? $data['documento']);
                    $personTmp->name = 'PGE - JUCEPA: ' . ($resp['documento_consultado'] ?? $data['documento']);
                    $personTmp->cpf = $resp['documento_consultado'] ?? $data['documento'];
                    $personTmp->mother = '';
                    $personTmp->meta = ['jucepa' => $jucepa];

                    session()->put('request_search', ['options' => ['pge_jucepa'], 'cpf' => $data['documento']]);
                    $userId = auth()->id() ?? 'guest';
                    cache()->put('person_search_' . $userId, ['pge_jucepa' => collect([$personTmp])], now()->addMinute(1));

                    return redirect()->route('person.search.index');
                }
                if ($routeName === 'pge.adepara') {
                    if ($request->ajax()) {
                        return view('person.partials.adepara', ['adepara' => $adepara, 'documento' => $data['documento']]);
                    }
                    $viewBase = 'adepara';
                    return view('person.view.show', [
                        'person' => $person,
                        'detran' => $detran,
                        'adepara' => $adepara,
                        'semas' => $semas,
                        'documento' => $data['documento'],
                        'view_base' => $viewBase,
                    ]);
                }
                if ($routeName === 'pge.detran') {
                    if ($request->ajax()) {
                        return view('person.partials.detran', ['detran' => $detran, 'documento' => $data['documento']]);
                    }
                    $viewBase = 'detran';
                    return view('person.view.show', [
                        'person' => $person,
                        'detran' => $detran,
                        'adepara' => $adepara,
                        'semas' => $semas,
                        'documento' => $data['documento'],
                        'view_base' => $viewBase,
                    ]);
                }

                // Se for requisição AJAX genérica, retornar partial conforme disponibilidade
                if ($request->ajax()) {
                    if (!empty($adepara)) {
                        return view('person.partials.adepara', ['adepara' => $adepara, 'documento' => $data['documento']]);
                    }
                    if (!empty($semas)) {
                        return view('person.partials.semas', ['semas' => $semas, 'documento' => $data['documento']]);
                    }
                    if (!empty($jucepa)) {
                        return view('person.partials.jucepa', ['jucepa' => $jucepa, 'documento' => $data['documento']]);
                    }
                    return view('person.partials.detran', ['detran' => $detran, 'documento' => $data['documento']]);
                }

                // Renderiza a view de pessoa principal informando qual base deve ser exibida
                // Ao renderizar a view completa, priorizar ADEPARA quando disponível
                $viewBase = 'detran';
                if (!empty($adepara)) {
                    $viewBase = 'adepara';
                } elseif (!empty($semas)) {
                    $viewBase = 'semas';
                }

                return view('person.view.show', [
                    'person' => $person,
                    'detran' => $detran,
                    'adepara' => $adepara,
                    'semas' => $semas,
                    'documento' => $data['documento'],
                    'view_base' => $viewBase,
                ]);
            }

            return response()->json(['data' => $resp], 200);
        } catch (\Exception $e) {
            Log::error('PgeController::consult exception: ' . $e->getMessage(), ['documento' => $data['documento']]);
            return response()->json(['error' => 'Erro interno ao consultar PGE'], 500);
        }
    }
}
