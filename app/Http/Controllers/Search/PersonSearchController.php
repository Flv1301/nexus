<?php

namespace App\Http\Controllers\Search;

use App\APIs\CortexApi;
use App\APIs\PgeApi;
use App\Helpers\Str as StrHerlper;
use App\Http\Controllers\Controller;
use App\Models\Person\Person;
use App\Services\PersonSearchService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PersonSearchController extends Controller
{
    /**
     * @return Factory|View|Application
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function index(): Factory|View|Application
    {
        $bases = $this->getCachedBases();
        $request = $this->getRequestSession();

        // Support loading a PGE base directly via query params, e.g.
        // /pesquisa/pessoa?view_base=semas&documento=12345678901
        $viewBase = request()->query('view_base');
        $documento = request()->query('documento');

        if ($viewBase && $documento) {
            $viewBase = strtolower($viewBase);
            if (in_array($viewBase, ['semas', 'adepara', 'detran', 'jucepa'])) {
                $method = 'pge_' . $viewBase;
                if (method_exists($this, $method)) {
                    $fakeRequest = new Request(['cpf' => $documento]);
                    $result = $this->$method($fakeRequest);
                    $bases['pge_' . $viewBase] = $result;

                    // Prepare the request object so the search UI shows the correct inputs/checkboxes
                    $request = new Request(['options' => ['pge_' . $viewBase], 'cpf' => $documento]);

                    // Cache the generated bases for the current user so reload/back works
                    $id = Auth::id();
                    if ($id) {
                        cache()->put('person_search_' . $id, $bases, now()->addMinutes(5));
                        session()->put('request_search', $request->except('_token'));
                    }
                }
            }
        }

        // retorna as bases e a requisição na view
        return view('search.person.index', compact('bases', 'request'));
    }

    /**
     * @return array|mixed
     */
    private function getCachedBases(): mixed
    {
        $id = Auth::id();

        if (Cache::has('person_search_' . $id)) {
            return Cache::get('person_search_' . $id);
        }
        return [];
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function search(Request $request): View|Factory|RedirectResponse|Application
    {
        try {
            // Logar opções recebidas para diagnosticar problemas com nomes de método
            try {
                Log::info('PersonSearchController::search received options', ['options_raw' => $request->options ?? null]);
            } catch (\Throwable $e) {
                // não interromper fluxo por falha de log
            }
            if (!$request->filled('options')) {
                toast('Por favor, selecione alguma base de dados para realizar a pesquisa.', 'info');

                return back()->withInput();
            }

            if (!$this->inputFilterRequestEmpty($request->except('options', '_token'))) {
                toast('Por favor, preencha pelo menos um campo para realizar a pesquisa.', 'info');
                return back()->withInput();
            }

            #StrHerlper::asciiRequest($request, $request->except('_token', 'options'));
            StrHerlper::upperRequest($request, $request->except('_token', 'options'));

            $id = Auth::id();
            $inputHash = $this->generateInputHash($request);

            if (Session::has('hashInput') && Session::get('hashInput') !== $inputHash) {
                Cache::forget('person_search_' . $id);
            }

            $bases = cache()->remember('person_search_' . $id, now()->addMinute(5), function () use ($request) {
                $bases = [];
                foreach ($request->options as $option) {
                    // Normalizar opção: permitir tanto '-' quanto '_' e remover espaços invisíveis
                    $sanitized = trim(str_replace('-', '_', $option));

                    if (method_exists($this, $sanitized)) {
                        $result = $this->$sanitized($request);
                        // Determinar contagem de itens retornados
                        $count = 0;
                        try {
                            if (is_object($result) && method_exists($result, 'count')) {
                                $count = $result->count();
                            } elseif (is_array($result)) {
                                $count = count($result);
                            }
                        } catch (\Throwable $e) {
                            $count = 0;
                        }
                        // Remover log muito verboso de contagem por opção
                        $bases[$option] = $result;
                    } else {
                        // Registrar para debug e evitar exceção de método inexistente
                        Log::warning('PersonSearchController::search missing method for option', ['option' => $option, 'sanitized' => $sanitized]);
                        $bases[$option] = collect([]);
                    }
                }

                return $bases;
            });
            session()->put('hashInput', $inputHash);
            session()->put('request_search', $request->except('_token'));

            return view('search.person.index', compact('bases', 'request'));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            toast('Erro de sistema! Não foi possível realizar a busca', 'error');

            return back()->withInput();
        }
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function bnmp(Request $request): Collection
    {
        $excludedFields = $request->except('father', 'rg', 'lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }
        $bnmpCortex = new CortexApi();
        $data = collect([]);

        if ($request->filled('name', 'mother')) {
            $response = $bnmpCortex->personSearchBnmpNameAndMother($request->name, $request->mother);
            if ($response && !array_key_exists('error', $response)) {
                $data = $data->concat($this->getPersonsCortexBnmp($response));
            }
        }

        if ($request->filled('name', 'birth_date')) {
            $response = $bnmpCortex->personSearchBnmpNameAndBirthDate($request->name, $request->birth_date);
            if ($response && !array_key_exists('error', $response)) {
                $data = $data->concat($this->getPersonsCortexBnmp($response));
            }
        }

        if ($request->filled('cpf')) {
            $response = $bnmpCortex->personSearchBnmpCpf($request->cpf);
            if ($response && !array_key_exists('error', $response)) {
                $data->push($this->getPersonCortexBnmp($response[0]));
            }
        }

        return $data->unique();
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function nexus(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $query = Person::with('images')
            ->where('active_orcrim', false) // Exclui pessoas faccionadas (ativas)
            // Campos diretos da tabela persons
            ->when($request->name, function ($query, $name) {
                $nameUpper = Str::upper($name);
                $nameAscii = Str::ascii($nameUpper);
                return $query->where(function ($q) use ($nameUpper, $nameAscii) {
                    $q->where('name', 'ilike', '%' . $nameUpper . '%')
                        ->orWhere('name', 'ilike', '%' . $nameAscii . '%')
                        ->orWhere('nickname', 'ilike', '%' . $nameUpper . '%')
                        ->orWhere('nickname', 'ilike', '%' . $nameAscii . '%');
                });
            })
            ->when($request->cpf, function ($query, $cpf) {
                return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
            })
            ->when($request->cnpj, function ($query, $cnpj) {
                return $query->where('cnpj', 'like', '%' . Str::upper($cnpj) . '%');
            })
            ->when($request->rg, function ($query, $rg) {
                return $query->where('rg', 'like', '%' . Str::upper($rg) . '%');
            })
            ->when($request->father, function ($query, $father) {
                $fatherUpper = Str::upper($father);
                $fatherAscii = Str::ascii($fatherUpper);
                return $query->where(function ($q) use ($fatherUpper, $fatherAscii) {
                    $q->where('father', 'ilike', '%' . $fatherUpper . '%')
                        ->orWhere('father', 'ilike', '%' . $fatherAscii . '%');
                });
            })
            ->when($request->mother, function ($query, $mother) {
                $motherUpper = Str::upper($mother);
                $motherAscii = Str::ascii($motherUpper);
                return $query->where(function ($q) use ($motherUpper, $motherAscii) {
                    $q->where('mother', 'ilike', '%' . $motherUpper . '%')
                        ->orWhere('mother', 'ilike', '%' . $motherAscii . '%');
                });
            })
            ->when($request->birth_date, function ($query, $birthDate) {
                return $query->where('birth_date', StrHerlper::convertDateToEnUs($birthDate));
            })
            // Novos campos diretos da tabela persons
            ->when($request->birth_city, function ($query, $birthCity) {
                $birthCityUpper = Str::upper($birthCity);
                $birthCityAscii = Str::upper(Str::ascii($birthCity));

                return $query->where(function ($q) use ($birthCityUpper, $birthCityAscii) {
                    // Busca tradicional com acentos
                    $q->where('birth_city', 'ilike', '%' . $birthCityUpper . '%')
                        // Busca insensível a acentos usando TRANSLATE
                        ->orWhereRaw("UPPER(TRANSLATE(birth_city,
                          'ÁÀÃÂÄáàãâäÉÈÊËéèêëÍÌÎÏíìîïÓÒÕÔÖóòõôöÚÙÛÜúùûüÇçÑñ',
                          'AAAAAaaaaaEEEEeeeeIIIIiiiiOOOOOoooooUUUUuuuuCcNn'
                      )) ILIKE ?", ['%' . $birthCityAscii . '%']);
                });
            })
            ->when($request->tattoo, function ($query, $tattoo) {
                $tattooUpper = Str::upper($tattoo);
                $tattooAscii = Str::ascii($tattooUpper);
                return $query->where(function ($q) use ($tattooUpper, $tattooAscii) {
                    $q->where('tatto', 'ilike', '%' . $tattooUpper . '%')
                        ->orWhere('tatto', 'ilike', '%' . $tattooAscii . '%');
                });
            })
            ->when($request->orcrim, function ($query, $orcrim) {
                $orcrimUpper = Str::upper($orcrim);
                $orcrimAscii = Str::ascii($orcrimUpper);
                return $query->where(function ($q) use ($orcrimUpper, $orcrimAscii) {
                    $q->where('orcrim', 'ilike', '%' . $orcrimUpper . '%')
                        ->orWhere('orcrim', 'ilike', '%' . $orcrimAscii . '%');
                });
            })
            ->when($request->area_atuacao, function ($query, $areaAtuacao) {
                $areaUpper = Str::upper($areaAtuacao);
                $areaAscii = Str::ascii($areaUpper);
                return $query->where(function ($q) use ($areaUpper, $areaAscii) {
                    $q->where('orcrim_occupation_area', 'ilike', '%' . $areaUpper . '%')
                        ->orWhere('orcrim_occupation_area', 'ilike', '%' . $areaAscii . '%');
                });
            })
            ->when($request->matricula, function ($query, $matricula) {
                return $query->where('orcrim_matricula', 'like', '%' . Str::upper($matricula) . '%');
            })
            // Campos de tabelas relacionadas - usando whereHas para manter AND logic
            ->when($request->phone, function ($query, $phone) {
                $phoneClean = preg_replace('/\D/', '', $phone);
                return $query->whereHas('telephones', function ($subQuery) use ($phoneClean) {
                    $subQuery->where(function ($q) use ($phoneClean) {
                        // Busca por número completo (DDD + telefone concatenados)
                        $q->whereRaw("CONCAT(ddd, telephone) LIKE ?", ['%' . $phoneClean . '%'])
                            // Busca apenas pelo número do telefone (sem DDD)
                            ->orWhere('telephone', 'like', '%' . $phoneClean . '%');

                        // Se o número tem 11 dígitos, separar DDD dos primeiros 2 dígitos
                        if (strlen($phoneClean) === 11) {
                            $ddd = substr($phoneClean, 0, 2);
                            $numero = substr($phoneClean, 2);
                            $q->orWhere(function ($subQ) use ($ddd, $numero) {
                                $subQ->where('ddd', $ddd)
                                    ->where('telephone', 'like', '%' . $numero . '%');
                            });
                        }
                        // Se o número tem 10 dígitos, separar DDD dos primeiros 2 dígitos
                        elseif (strlen($phoneClean) === 10) {
                            $ddd = substr($phoneClean, 0, 2);
                            $numero = substr($phoneClean, 2);
                            $q->orWhere(function ($subQ) use ($ddd, $numero) {
                                $subQ->where('ddd', $ddd)
                                    ->where('telephone', 'like', '%' . $numero . '%');
                            });
                        }
                    });
                });
            })
            ->when($request->email, function ($query, $email) {
                $emailUpper = Str::upper($email);
                return $query->whereHas('emails', function ($subQuery) use ($emailUpper) {
                    $subQuery->where('email', 'ilike', '%' . $emailUpper . '%');
                });
            })
            ->when($request->city, function ($query, $city) {
                $cityUpper = Str::upper($city);
                $cityAscii = Str::upper(Str::ascii($city));

                return $query->whereHas('address', function ($subQuery) use ($cityUpper, $cityAscii) {
                    $subQuery->where(function ($q) use ($cityUpper, $cityAscii) {
                        // Busca tradicional com acentos
                        $q->where('city', 'ilike', '%' . $cityUpper . '%')
                            // Busca insensível a acentos usando TRANSLATE
                            ->orWhereRaw("UPPER(TRANSLATE(city,
                              'ÁÀÃÂÄáàãâäÉÈÊËéèêëÍÌÎÏíìîïÓÒÕÔÖóòõôöÚÙÛÜúùûüÇçÑñ',
                              'AAAAAaaaaaEEEEeeeeIIIIiiiiOOOOOoooooUUUUuuuuCcNn'
                          )) ILIKE ?", ['%' . $cityAscii . '%']);
                    });
                });
            })
            ->when($request->placa, function ($query, $placa) {
                // Normalizar a placa removendo caracteres especiais (hífen, espaços, etc.)
                $placaNormalized = preg_replace('/[^A-Z0-9]/i', '', Str::upper($placa));
                return $query->whereHas('vehicles', function ($subQuery) use ($placaNormalized) {
                    // Buscar tanto com hífen quanto sem hífen para máxima compatibilidade
                    $subQuery->where(function ($q) use ($placaNormalized) {
                        // Busca sem caracteres especiais
                        $q->whereRaw("UPPER(REPLACE(REPLACE(plate, '-', ''), ' ', '')) LIKE ?", ['%' . $placaNormalized . '%'])
                            // Busca com hífen automático nas posições tradicionais
                            ->orWhere('plate', 'ilike', '%' . $placaNormalized . '%');

                        // Se a placa tem 7 caracteres, testar com hífen na posição 3
                        if (strlen($placaNormalized) === 7) {
                            $placaWithHyphen = substr($placaNormalized, 0, 3) . '-' . substr($placaNormalized, 3);
                            $q->orWhere('plate', 'ilike', '%' . $placaWithHyphen . '%');
                        }
                    });
                });
            })
            ->when($request->bo, function ($query, $bo) {
                $boUpper = Str::upper($bo);
                return $query->whereHas('pcpas', function ($subQuery) use ($boUpper) {
                    $subQuery->where('bo', 'like', '%' . $boUpper . '%');
                });
            })
            ->when($request->natureza, function ($query, $natureza) {
                $naturezaUpper = Str::upper($natureza);
                $naturezaAscii = Str::ascii($naturezaUpper);
                return $query->whereHas('pcpas', function ($subQuery) use ($naturezaUpper, $naturezaAscii) {
                    $subQuery->where(function ($q) use ($naturezaUpper, $naturezaAscii) {
                        $q->where('natureza', 'ilike', '%' . $naturezaUpper . '%')
                            ->orWhere('natureza', 'ilike', '%' . $naturezaAscii . '%');
                    });
                });
            })
            ->when($request->processo, function ($query, $processo) {
                $processoUpper = Str::upper($processo);
                return $query->whereHas('tjs', function ($subQuery) use ($processoUpper) {
                    $subQuery->where('processo', 'like', '%' . $processoUpper . '%');
                });
            })
            ->when($request->situacao, function ($query, $situacao) {
                return $query->where('situacao', $situacao);
            });

        return $query->limit(50)->get();
    }

    /**
     * Consultar PGE - DETRAN fonte
     * @param Request $request
     * @return Collection
     */
    public function pge_detran(Request $request): Collection
    {
        $excludedFields = $request->except('options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $documento = $request->cpf ?? $request->cnpj ?? null;
        if (empty($documento)) {
            return collect([]);
        }

        $client = new PgeApi();
        $resp = $client->get('consultamp', ['documento' => $documento]);

        $fontes = null;
        if (isset($resp['fontes'])) $fontes = $resp['fontes'];
        elseif (isset($resp['data']['fontes'])) $fontes = $resp['data']['fontes'];

        // Log das chaves retornadas pelo PGE para diagnóstico
        try {
            Log::info('PersonSearchController::pge_jucepa fontes keys', ['keys' => is_array($fontes) ? array_keys($fontes) : null]);
        } catch (\Throwable $e) {
            // ignora falha de log
        }

        if (empty($fontes)) {
            return collect([]);
        }

        $detran = [];
        foreach ($fontes as $k => $v) {
            if (strtoupper($k) === 'DETRAN') {
                $detran = $v;
                break;
            }
        }

        // Retornar uma única entrada contendo toda a lista DETRAN em meta['detran']
        $data = collect([]);
        if (!empty($detran) && is_array($detran)) {
            $person = new Person();
            $person->id = 'pge_detran_' . ($resp['documento_consultado'] ?? $documento);
            $person->name = 'PGE - DETRAN: ' . ($resp['documento_consultado'] ?? $documento);
            $person->cpf = $resp['documento_consultado'] ?? $documento;
            $person->mother = '';
            $person->meta = ['detran' => $detran];
            $data->push($person);
        }

        return $data;
    }

    /**
     * Consultar PGE - SEMAS fonte
     * @param Request $request
     * @return Collection
     */
    public function pge_semas(Request $request): Collection
    {
        $excludedFields = $request->except('options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $documento = $request->cpf ?? $request->cnpj ?? null;
        if (empty($documento)) {
            return collect([]);
        }

        $client = new PgeApi();
        $resp = $client->get('consultamp', ['documento' => $documento]);

        $fontes = null;
        if (isset($resp['fontes'])) $fontes = $resp['fontes'];
        elseif (isset($resp['data']['fontes'])) $fontes = $resp['data']['fontes'];

        if (empty($fontes)) {
            return collect([]);
        }

        $semas = [];
        foreach ($fontes as $k => $v) {
            if (strtoupper($k) === 'SEMAS') {
                $semas = $v;
                break;
            }
        }

        // Retornar única entrada com lista SEMAS em meta['semas']
        $data = collect([]);
        if (!empty($semas) && is_array($semas)) {
            $person = new Person();
            $person->id = 'pge_semas_' . ($resp['documento_consultado'] ?? $documento);
            $person->name = 'PGE - SEMAS: ' . ($resp['documento_consultado'] ?? $documento);
            $person->cpf = $resp['documento_consultado'] ?? $documento;
            $person->mother = '';
            $person->meta = ['semas' => $semas];
            $data->push($person);
        }

        return $data;
    }

    /**
     * Consultar PGE - ADEPARA fonte
     * @param Request $request
     * @return Collection
     */
    public function pge_adepara(Request $request): Collection
    {
        $excludedFields = $request->except('options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $documento = $request->cpf ?? $request->cnpj ?? null;
        if (empty($documento)) {
            return collect([]);
        }

        $client = new PgeApi();
        $resp = $client->get('consultamp', ['documento' => $documento]);

        $fontes = null;
        if (isset($resp['fontes'])) $fontes = $resp['fontes'];
        elseif (isset($resp['data']['fontes'])) $fontes = $resp['data']['fontes'];

        if (empty($fontes)) {
            return collect([]);
        }

        $adepara = [];
        foreach ($fontes as $k => $v) {
            if (strtoupper($k) === 'ADEPARA') {
                $adepara = $v;
                break;
            }
        }

        // Retornar única entrada com lista ADEPARA em meta['adepara']
        $data = collect([]);
        if (!empty($adepara) && is_array($adepara)) {
            $person = new Person();
            $person->id = 'pge_adepara_' . ($resp['documento_consultado'] ?? $documento);
            $person->name = 'PGE - ADEPARA: ' . ($resp['documento_consultado'] ?? $documento);
            $person->cpf = $resp['documento_consultado'] ?? $documento;
            $person->mother = '';
            $person->meta = ['adepara' => $adepara];
            $data->push($person);
        }

        return $data;
    }

    /**
     * Consultar PGE - JUCEPA fonte
     * @param Request $request
     * @return Collection
     */
    public function pge_jucepa(Request $request): Collection
    {
        $excludedFields = $request->except('options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $documento = $request->cpf ?? $request->cnpj ?? null;
        if (empty($documento)) {
            return collect([]);
        }

        $client = new PgeApi();
        $resp = $client->get('consultamp', ['documento' => $documento]);

        $fontes = null;
        if (isset($resp['fontes'])) $fontes = $resp['fontes'];
        elseif (isset($resp['data']['fontes'])) $fontes = $resp['data']['fontes'];

        if (empty($fontes)) {
            return collect([]);
        }

        $jucepa = [];
        if (is_array($fontes)) {
            foreach ($fontes as $k => $v) {
                if (strtoupper($k) === 'JUCEPA') {
                    $jucepa = $v;
                    break;
                }
            }
        }

        // Caso a API retorne chaves específicas ao invés de uma chave 'JUCEPA'
        // (ex.: 'DadosCadastrais', 'QuadroSocietario'), agregamos esses sub-blocos
        // em uma estrutura única `jucepa` para manter compatibilidade.
        if (empty($jucepa) || !is_array($jucepa)) {
            $collected = [];
            if (is_array($fontes)) {
                foreach ($fontes as $k => $v) {
                    $keyL = strtolower($k);
                    // Ignorar fontes que representem outras bases conhecidas
                    if (in_array($keyL, ['adepara', 'semas', 'detran'])) continue;

                    // Aceitar blocos que pareçam partes da JUCEPA
                    if (str_contains($keyL, 'dados') || str_contains($keyL, 'quadro') || str_contains($keyL, 'jucepa')) {
                        if (is_iterable($v)) {
                            // garantir array para inspeção
                            $collected[$k] = is_array($v) ? $v : (array)$v;
                        } else {
                            // tentar decodificar caso seja JSON string
                            if (is_string($v)) {
                                $decoded = json_decode($v, true);
                                if (is_array($decoded)) $collected[$k] = $decoded;
                            }
                        }
                    }
                }
            }

            if (!empty($collected)) {
                // Unificar em uma lista única mantendo a distinção por sub-chave
                $jucepa = $collected;
                Log::info('PersonSearchController::pge_jucepa aggregated jucepa from subkeys', ['keys' => array_keys($collected), 'counts' => array_map('count', $collected)]);
            }
        }

        // Retornar única entrada com lista JUCEPA em meta['jucepa']
        $data = collect([]);
        // Normalizar estrutura JUCEPA para chaves previsíveis: DadosCadastrais, QuadroSocietario
        if (!empty($jucepa) && is_array($jucepa)) {
            $normalized = ['DadosCadastrais' => [], 'QuadroSocietario' => []];

            if (array_key_exists('DadosCadastrais', $jucepa) || array_key_exists('dadosCadastrais', $jucepa)) {
                $normalized['DadosCadastrais'] = array_values($jucepa['DadosCadastrais'] ?? $jucepa['dadosCadastrais'] ?? []);
            }
            if (array_key_exists('QuadroSocietario', $jucepa) || array_key_exists('quadroSocietario', $jucepa)) {
                $normalized['QuadroSocietario'] = array_values($jucepa['QuadroSocietario'] ?? $jucepa['quadroSocietario'] ?? []);
            }

            // Se o $jucepa for um array indexado e parecer com DadosCadastrais, usá-lo diretamente
            if (empty($normalized['DadosCadastrais']) && array_values($jucepa) === $jucepa) {
                $first = $jucepa[0] ?? null;
                if (is_array($first) && (array_key_exists('documento', $first) || array_key_exists('pessoa', $first))) {
                    $normalized['DadosCadastrais'] = array_values($jucepa);
                }
            }

            // Caso existam outras chaves que correspondam ao conteúdo, tentar mapear
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

        if (!empty($jucepa) && (is_array($jucepa) || is_iterable($jucepa))) {
            // Log para diagnóstico antes de construir Person
            try {
                $keys = is_array($jucepa) ? array_keys($jucepa) : null;
                Log::info('PersonSearchController::pge_jucepa will create Person with jucepa', ['keys' => $keys]);
            } catch (\Throwable $e) {
                // ignore
            }

            $person = new Person();
            $person->id = 'pge_jucepa_' . ($resp['documento_consultado'] ?? $documento);
            $person->name = 'PGE - JUCEPA: ' . ($resp['documento_consultado'] ?? $documento);
            $person->cpf = $resp['documento_consultado'] ?? $documento;
            $person->mother = '';
            $person->meta = ['jucepa' => $jucepa];
            $data->push($person);
        }

        return $data;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function cortex(Request $request): Collection
    {
        $excludedFields = $request->except('father', 'rg', 'lastname', 'options', '_token');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $cortex = new CortexApi();
        $data = collect([]);
        $response = null;

        if ($request->filled('name', 'mother')) {
            $response = $cortex->personSearchNameAndMother($request->name, $request->mother);

            if ($response && !array_key_exists('error', $response)) {
                foreach ($response as $value) {
                    $data->push($this->getPersonCortex($value));
                }
            }
        }

        if ($request->filled('name', 'birth_date') && !$response) {
            $response = $cortex->personSearchNameAndBirthDate($request->name, $request->birth_date);

            if ($response && !array_key_exists('error', $response)) {
                foreach ($response as $value) {
                    $data->push($this->getPersonCortex($value));
                }
            }
        }

        if ($request->cpf) {
            $response = $cortex->personSearchCPF($request->cpf);

            if ($response && !array_key_exists('error', $response)) {
                $data->push($this->getPersonCortex($response));
            }
        }

        return $data->unique();
    }

    /**
     * @param array $value
     * @return Person
     */
    private function getPersonCortex(array $value): Person
    {
        $person = new Person();
        $person->id = $value['numeroCPF'] ?? '';
        $person->name = ($value['nomeCompleto'] ?? '') . ' - UF: ' . ($value['uf'] ?? '');
        $person->cpf = $value['numeroCPF'] ?? '';
        $person->mother = $value['nomeMae'] ?? '';
        $person->birth_date = $value['dataNascimento'] ?? '';

        return $person;
    }

    /**
     * @param array $value
     * @return Person
     */
    private function getPersonCortexBnmp(array $value): Person
    {
        $person = new Person();

        if ($value) {
            $person->id = $value['idpessoa'] ?? '';
            $person->name = ($value['nome'] ?? '') . " ( " . ($value['statusPessoa'] ?? '') . " )";
            $person->mother = $value['nomeMae'] ?? '';
            $person->birth_date = $value['dataNascimento'] ?? '';
        }

        return $person;
    }

    /**
     * @param $base
     * @param $id
     * @return Factory|View|Application
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function show($base, $id): Factory|View|Application
    {
        $request = $this->getRequestSession();
        $service = new PersonSearchService();
        $person = $service->$base($id);

        return view('search.person.index', compact('base', 'person', 'request'));
    }


    /**
     * @param Request $request
     * @return string
     */
    protected function generateInputHash(Request $request): string
    {
        $cacheKey = implode('|', $request->options) . '|' . implode('|', $request->except('_token', 'options'));
        return hash('md5', $cacheKey);
    }

    /**
     * @param array $inputs
     * @return array
     */
    protected function inputFilterRequestEmpty(array $inputs): array
    {
        return Arr::where($inputs, function ($value) {
            return !empty($value);
        });
    }

    /**
     * @return Request
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getRequestSession(): Request
    {
        $requestSearch = session()->get('request_search');
        return $requestSearch ? new Request($requestSearch) : new Request(['options' => []]);
    }

    /**
     * Gera relatório completo da pessoa
     * @param int $id
     * @return View
     */
    public function report($id): View
    {
        $person = Person::with([
            'address',
            'telephones',
            'emails',
            'socials',
            'images',
            'companies',
            'vehicles',
            'vinculoOrcrims',
            'pcpas',
            'tjs',
            'armas',
            'rais',
            'bancarios',
            'docs'
        ])->findOrFail($id);

        return view('search.person.report', compact('person'));
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function faccionado(Request $request): Collection
    {
        $excludedFields = $request->except('lastname', 'options', '_token', 'faccionado');

        if (!$this->inputFilterRequestEmpty($excludedFields)) {
            return collect([]);
        }

        $query = Person::with('images')
            ->where('active_orcrim', true) // Apenas pessoas faccionadas (ativas)
            // Campos diretos da tabela persons
            ->when($request->name, function ($query, $name) {
                $nameUpper = Str::upper($name);
                $nameAscii = Str::ascii($nameUpper);
                return $query->where(function ($q) use ($nameUpper, $nameAscii) {
                    $q->where('name', 'ilike', '%' . $nameUpper . '%')
                        ->orWhere('name', 'ilike', '%' . $nameAscii . '%')
                        ->orWhere('nickname', 'ilike', '%' . $nameUpper . '%')
                        ->orWhere('nickname', 'ilike', '%' . $nameAscii . '%');
                });
            })
            ->when($request->cpf, function ($query, $cpf) {
                return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
            })
            ->when($request->cnpj, function ($query, $cnpj) {
                return $query->where('cnpj', 'like', '%' . Str::upper($cnpj) . '%');
            })
            ->when($request->rg, function ($query, $rg) {
                return $query->where('rg', 'like', '%' . Str::upper($rg) . '%');
            })
            ->when($request->father, function ($query, $father) {
                $fatherUpper = Str::upper($father);
                $fatherAscii = Str::ascii($fatherUpper);
                return $query->where(function ($q) use ($fatherUpper, $fatherAscii) {
                    $q->where('father', 'ilike', '%' . $fatherUpper . '%')
                        ->orWhere('father', 'ilike', '%' . $fatherAscii . '%');
                });
            })
            ->when($request->mother, function ($query, $mother) {
                $motherUpper = Str::upper($mother);
                $motherAscii = Str::ascii($motherUpper);
                return $query->where(function ($q) use ($motherUpper, $motherAscii) {
                    $q->where('mother', 'ilike', '%' . $motherUpper . '%')
                        ->orWhere('mother', 'ilike', '%' . $motherAscii . '%');
                });
            })
            ->when($request->birth_date, function ($query, $birthDate) {
                return $query->where('birth_date', StrHerlper::convertDateToEnUs($birthDate));
            })
            // Novos campos diretos da tabela persons
            ->when($request->birth_city, function ($query, $birthCity) {
                $birthCityUpper = Str::upper($birthCity);
                $birthCityAscii = Str::upper(Str::ascii($birthCity));

                return $query->where(function ($q) use ($birthCityUpper, $birthCityAscii) {
                    // Busca tradicional com acentos
                    $q->where('birth_city', 'ilike', '%' . $birthCityUpper . '%')
                        // Busca insensível a acentos usando TRANSLATE
                        ->orWhereRaw("UPPER(TRANSLATE(birth_city,
                          'ÁÀÃÂÄáàãâäÉÈÊËéèêëÍÌÎÏíìîïÓÒÕÔÖóòõôöÚÙÛÜúùûüÇçÑñ',
                          'AAAAAaaaaaEEEEeeeeIIIIiiiiOOOOOoooooUUUUuuuuCcNn'
                      )) ILIKE ?", ['%' . $birthCityAscii . '%']);
                });
            })
            ->when($request->tattoo, function ($query, $tattoo) {
                $tattooUpper = Str::upper($tattoo);
                $tattooAscii = Str::ascii($tattooUpper);
                return $query->where(function ($q) use ($tattooUpper, $tattooAscii) {
                    $q->where('tatto', 'ilike', '%' . $tattooUpper . '%')
                        ->orWhere('tatto', 'ilike', '%' . $tattooAscii . '%');
                });
            })
            ->when($request->orcrim, function ($query, $orcrim) {
                $orcrimUpper = Str::upper($orcrim);
                $orcrimAscii = Str::ascii($orcrimUpper);
                return $query->where(function ($q) use ($orcrimUpper, $orcrimAscii) {
                    $q->where('orcrim', 'ilike', '%' . $orcrimUpper . '%')
                        ->orWhere('orcrim', 'ilike', '%' . $orcrimAscii . '%');
                });
            })
            ->when($request->area_atuacao, function ($query, $areaAtuacao) {
                $areaUpper = Str::upper($areaAtuacao);
                $areaAscii = Str::ascii($areaUpper);
                return $query->where(function ($q) use ($areaUpper, $areaAscii) {
                    $q->where('orcrim_occupation_area', 'ilike', '%' . $areaUpper . '%')
                        ->orWhere('orcrim_occupation_area', 'ilike', '%' . $areaAscii . '%');
                });
            })
            ->when($request->matricula, function ($query, $matricula) {
                return $query->where('orcrim_matricula', 'like', '%' . Str::upper($matricula) . '%');
            })
            // Campos de tabelas relacionadas - usando EXISTS para manter AND logic
            ->when($request->phone, function ($query, $phone) {
                $phoneClean = preg_replace('/\D/', '', $phone);
                return $query->whereExists(function ($subQuery) use ($phoneClean) {
                    $subQuery->select(DB::raw(1))
                        ->from('person_telephones')
                        ->join('telephones', 'person_telephones.telephone_id', '=', 'telephones.id')
                        ->whereColumn('person_telephones.person_id', 'persons.id')
                        ->where(function ($q) use ($phoneClean) {
                            // Busca por número completo (DDD + telefone concatenados)
                            $q->whereRaw("CONCAT(telephones.ddd, telephones.telephone) LIKE ?", ['%' . $phoneClean . '%'])
                                // Busca apenas pelo número do telefone (sem DDD)
                                ->orWhere('telephones.telephone', 'like', '%' . $phoneClean . '%');

                            // Se o número tem 11 dígitos, separar DDD dos primeiros 2 dígitos
                            if (strlen($phoneClean) === 11) {
                                $ddd = substr($phoneClean, 0, 2);
                                $numero = substr($phoneClean, 2);
                                $q->orWhere(function ($subQ) use ($ddd, $numero) {
                                    $subQ->where('telephones.ddd', $ddd)
                                        ->where('telephones.telephone', 'like', '%' . $numero . '%');
                                });
                            }
                            // Se o número tem 10 dígitos, separar DDD dos primeiros 2 dígitos
                            elseif (strlen($phoneClean) === 10) {
                                $ddd = substr($phoneClean, 0, 2);
                                $numero = substr($phoneClean, 2);
                                $q->orWhere(function ($subQ) use ($ddd, $numero) {
                                    $subQ->where('telephones.ddd', $ddd)
                                        ->where('telephones.telephone', 'like', '%' . $numero . '%');
                                });
                            }
                        });
                });
            })
            ->when($request->email, function ($query, $email) {
                $emailUpper = Str::upper($email);
                return $query->whereExists(function ($subQuery) use ($emailUpper) {
                    $subQuery->select(DB::raw(1))
                        ->from('person_emails')
                        ->join('emails', 'person_emails.email_id', '=', 'emails.id')
                        ->whereColumn('person_emails.person_id', 'persons.id')
                        ->where('emails.email', 'ilike', '%' . $emailUpper . '%');
                });
            })
            ->when($request->city, function ($query, $city) {
                $cityUpper = Str::upper($city);
                $cityAscii = Str::upper(Str::ascii($city));

                return $query->whereExists(function ($subQuery) use ($cityUpper, $cityAscii) {
                    $subQuery->select(DB::raw(1))
                        ->from('person_address')
                        ->join('address', 'person_address.address_id', '=', 'address.id')
                        ->whereColumn('person_address.person_id', 'persons.id')
                        ->where(function ($q) use ($cityUpper, $cityAscii) {
                            // Busca tradicional com acentos
                            $q->where('address.city', 'ilike', '%' . $cityUpper . '%')
                                // Busca insensível a acentos usando TRANSLATE
                                ->orWhereRaw("UPPER(TRANSLATE(address.city,
                                      'ÁÀÃÂÄáàãâäÉÈÊËéèêëÍÌÎÏíìîïÓÒÕÔÖóòõôöÚÙÛÜúùûüÇçÑñ',
                                      'AAAAAaaaaaEEEEeeeeIIIIiiiiOOOOOoooooUUUUuuuuCcNn'
                                  )) ILIKE ?", ['%' . $cityAscii . '%']);
                        });
                });
            })
            ->when($request->placa, function ($query, $placa) {
                // Normalizar a placa removendo caracteres especiais (hífen, espaços, etc.)
                $placaNormalized = preg_replace('/[^A-Z0-9]/i', '', Str::upper($placa));
                return $query->whereExists(function ($subQuery) use ($placaNormalized) {
                    $subQuery->select(DB::raw(1))
                        ->from('vehicles')
                        ->whereColumn('vehicles.person_id', 'persons.id')
                        // Buscar tanto com hífen quanto sem hífen para máxima compatibilidade
                        ->where(function ($q) use ($placaNormalized) {
                            // Busca sem caracteres especiais
                            $q->whereRaw("UPPER(REPLACE(REPLACE(vehicles.plate, '-', ''), ' ', '')) LIKE ?", ['%' . $placaNormalized . '%'])
                                // Busca com hífen automático nas posições tradicionais
                                ->orWhere('vehicles.plate', 'ilike', '%' . $placaNormalized . '%');

                            // Se a placa tem 7 caracteres, testar com hífen na posição 3
                            if (strlen($placaNormalized) === 7) {
                                $placaWithHyphen = substr($placaNormalized, 0, 3) . '-' . substr($placaNormalized, 3);
                                $q->orWhere('vehicles.plate', 'ilike', '%' . $placaWithHyphen . '%');
                            }
                        });
                });
            })
            ->when($request->bo, function ($query, $bo) {
                $boUpper = Str::upper($bo);
                return $query->whereExists(function ($subQuery) use ($boUpper) {
                    $subQuery->select(DB::raw(1))
                        ->from('pcpas')
                        ->whereColumn('pcpas.person_id', 'persons.id')
                        ->where('pcpas.bo', 'like', '%' . $boUpper . '%');
                });
            })
            ->when($request->natureza, function ($query, $natureza) {
                $naturezaUpper = Str::upper($natureza);
                $naturezaAscii = Str::ascii($naturezaUpper);
                return $query->whereExists(function ($subQuery) use ($naturezaUpper, $naturezaAscii) {
                    $subQuery->select(DB::raw(1))
                        ->from('pcpas')
                        ->whereColumn('pcpas.person_id', 'persons.id')
                        ->where(function ($q) use ($naturezaUpper, $naturezaAscii) {
                            $q->where('natureza', 'ilike', '%' . $naturezaUpper . '%')
                                ->orWhere('natureza', 'ilike', '%' . $naturezaAscii . '%');
                        });
                });
            })
            ->when($request->processo, function ($query, $processo) {
                $processoUpper = Str::upper($processo);
                return $query->whereExists(function ($subQuery) use ($processoUpper) {
                    $subQuery->select(DB::raw(1))
                        ->from('tjs')
                        ->whereColumn('tjs.person_id', 'persons.id')
                        ->where('tjs.processo', 'like', '%' . $processoUpper . '%');
                });
            })
            ->when($request->situacao, function ($query, $situacao) {
                return $query->where('situacao', $situacao);
            });

        return $query->limit(50)->get();
    }

    /**
     * Normaliza acentos para busca insensível a acentos
     * @param string $text
     * @return array
     */
    private function normalizeAccents(string $text): array
    {
        $variations = [];
        $upperText = Str::upper($text);

        // Adicionar texto original
        $variations[] = $upperText;

        // Usar Str::ascii para remover acentos
        $ascii = Str::ascii($upperText);
        $variations[] = $ascii;

        // Mapeamento manual de caracteres acentuados mais comuns
        $accentMap = [
            'Á' => 'A',
            'À' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'Ä' => 'A',
            'É' => 'E',
            'È' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Í' => 'I',
            'Ì' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ó' => 'O',
            'Ò' => 'O',
            'Õ' => 'O',
            'Ô' => 'O',
            'Ö' => 'O',
            'Ú' => 'U',
            'Ù' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ç' => 'C',
            'Ñ' => 'N'
        ];

        $manualNormalized = $upperText;
        foreach ($accentMap as $accented => $plain) {
            $manualNormalized = str_replace($accented, $plain, $manualNormalized);
        }
        $variations[] = $manualNormalized;
        // Remover duplicatas e valores vazios
        return array_filter(array_unique($variations));
    }

    /**
     * Cria uma cláusula SQL para busca insensível a acentos
     * @param string $fieldName
     * @param string $searchTerm
     * @return string
     */
    private function createAccentInsensitiveClause(string $fieldName, string $searchTerm): string
    {
        $normalizedTerm = Str::upper(Str::ascii($searchTerm));

        // SQL que normaliza acentos tanto no campo quanto no termo de busca
        return "UPPER(TRANSLATE($fieldName,
            'ÁÀÃÂÄáàãâäÉÈÊËéèêëÍÌÎÏíìîïÓÒÕÔÖóòõôöÚÙÛÜúùûüÇçÑñ',
            'AAAAAaaaaaEEEEeeeeIIIIiiiiOOOOOoooooUUUUuuuuCcNn'
        )) ILIKE '%$normalizedTerm%'";
    }

    /**
     * Exibe a página de pesquisa avançada
     * @return Factory|View|Application
     */
    public function advancedIndex(): Factory|View|Application
    {
        $bases = $this->getCachedAdvancedBases();
        $request = $this->getAdvancedRequestSession();

        return view('search.person.advanced', compact('bases', 'request'));
    }

    /**
     * Executa a pesquisa avançada com campos dinâmicos
     * @param Request $request
     * @return View|Factory|RedirectResponse|Application
     */
    public function advancedSearch(Request $request): View|Factory|RedirectResponse|Application
    {
        try {
            // Log para debug
            Log::info('AdvancedSearch request received', [
                'criteria' => $request->criteria,
                'bases' => $request->bases,
                'all_data' => $request->all()
            ]);

            // Validar se existem critérios de pesquisa
            if (!$request->filled('criteria') || empty($request->criteria)) {
                toast('Por favor, adicione pelo menos um critério de pesquisa.', 'info');
                return back()->withInput();
            }

            // Validar se pelo menos uma base foi selecionada
            if (!$request->filled('bases') || empty($request->bases)) {
                toast('Por favor, selecione pelo menos uma base de dados para pesquisar.', 'info');
                return back()->withInput();
            }

            // Validar se os critérios estão válidos
            $validCriteria = false;
            foreach ($request->criteria as $criterion) {
                if (!empty($criterion['field']) && !empty($criterion['value'])) {
                    $validCriteria = true;
                    break;
                }
            }

            if (!$validCriteria) {
                toast('Por favor, preencha pelo menos um critério válido de pesquisa.', 'info');
                return back()->withInput();
            }

            $id = Auth::id();
            $inputHash = $this->generateAdvancedInputHash($request);

            if (Session::has('hashAdvancedInput') && Session::get('hashAdvancedInput') !== $inputHash) {
                Cache::forget('person_advanced_search_' . $id);
            }

            $bases = cache()->remember('person_advanced_search_' . $id, now()->addMinute(5), function () use ($request) {
                $bases = [];
                
                foreach ($request->bases as $base) {
                    try {
                        $bases[$base] = $this->executeAdvancedSearch($request, $base);
                        Log::info("Search executed for base: $base", ['count' => $bases[$base]->count()]);
                    } catch (Exception $e) {
                        Log::error("Error searching base $base: " . $e->getMessage());
                        $bases[$base] = collect([]);
                    }
                }

                return $bases;
            });

            session()->put('hashAdvancedInput', $inputHash);
            session()->put('request_advanced_search', $request->except('_token'));

            return view('search.person.advanced', compact('bases', 'request'));
        } catch (Exception $exception) {
            Log::error('Erro na pesquisa avançada: ' . $exception->getMessage(), [
                'trace' => $exception->getTraceAsString()
            ]);
            toast('Erro de sistema! Não foi possível realizar a busca: ' . $exception->getMessage(), 'error');
            return back()->withInput();
        }
    }

    /**
     * Executa a pesquisa avançada para uma base específica
     */
    private function executeAdvancedSearch(Request $request, string $base): Collection
    {
        // Aplicar a query na base específica usando os métodos existentes
        switch ($base) {
            case 'nexus':
                return $this->executeAdvancedNexusSearch($request);
            case 'faccionado':
                return $this->executeAdvancedFaccionadoSearch($request);
            default:
                return collect([]);
        }
    }

    /**
     * Executa busca avançada na base Nexus
     */
    private function executeAdvancedNexusSearch(Request $request): Collection
    {
        // Converter os critérios para request padrão
        $searchRequest = $this->convertCriteriaToRequest($request);
        
        // Usar o método nexus existente
        return $this->nexus($searchRequest);
    }

    /**
     * Executa busca avançada na base Faccionado
     */
    private function executeAdvancedFaccionadoSearch(Request $request): Collection
    {
        // Converter os critérios para request padrão
        $searchRequest = $this->convertCriteriaToRequest($request);
        
        // Usar o método faccionado existente
        return $this->faccionado($searchRequest);
    }

    /**
     * Converte os critérios da pesquisa avançada para formato de request padrão
     */
    private function convertCriteriaToRequest(Request $request): Request
    {
        $criteria = $request->criteria ?? [];
        $requestData = [];

        foreach ($criteria as $criterion) {
            if (empty($criterion['field']) || empty($criterion['value'])) {
                continue;
            }

            $field = $criterion['field'];
            $value = $criterion['value'];
            $operator = $criterion['operator'] ?? 'contains';

            // Para operadores que não são "contains", ajustar o valor
            switch ($operator) {
                case 'equals':
                    $requestData[$field] = $value;
                    break;
                case 'starts_with':
                    $requestData[$field] = $value . '%';
                    break;
                case 'ends_with':
                    $requestData[$field] = '%' . $value;
                    break;
                case 'contains':
                default:
                    $requestData[$field] = $value;
                    break;
            }
        }

        // Adicionar campos de configuração
        if ($request->order_field) {
            $requestData['order_field'] = $request->order_field;
        }
        if ($request->order_direction) {
            $requestData['order_direction'] = $request->order_direction;
        }

        return new Request($requestData);
    }

    /**
     * Gera hash para identificar mudanças na pesquisa avançada
     */
    private function generateAdvancedInputHash(Request $request): string
    {
        $data = [
            'criteria' => $request->criteria ?? [],
            'bases' => $request->bases ?? [],
            'order_field' => $request->order_field ?? '',
            'order_direction' => $request->order_direction ?? 'asc'
        ];
        
        return md5(serialize($data));
    }

    /**
     * Recupera bases em cache para pesquisa avançada
     */
    private function getCachedAdvancedBases(): array
    {
        $id = Auth::id();

        if (Cache::has('person_advanced_search_' . $id)) {
            return Cache::get('person_advanced_search_' . $id);
        }
        return [];
    }

    /**
     * Recupera request da sessão para pesquisa avançada
     */
    private function getAdvancedRequestSession(): Request
    {
        if (Session::has('request_advanced_search')) {
            return new Request(Session::get('request_advanced_search'));
        }
        return new Request();
    }
}
