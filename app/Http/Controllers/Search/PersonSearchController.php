<?php

namespace App\Http\Controllers\Search;

use App\APIs\CortexApi;
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
                    $bases[$option] = $this->$option($request);
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
                return $query->where(function($q) use ($nameUpper, $nameAscii) {
                    $q->where('name', 'ilike', '%' . $nameUpper . '%')
                      ->orWhere('name', 'ilike', '%' . $nameAscii . '%')
                      ->orWhere('nickname', 'ilike', '%' . $nameUpper . '%')
                      ->orWhere('nickname', 'ilike', '%' . $nameAscii . '%');
                });
            })
            ->when($request->cpf, function ($query, $cpf) {
                return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
            })
            ->when($request->rg, function ($query, $rg) {
                return $query->where('rg', 'like', '%' . Str::upper($rg) . '%');
            })
            ->when($request->father, function ($query, $father) {
                $fatherUpper = Str::upper($father);
                $fatherAscii = Str::ascii($fatherUpper);
                return $query->where(function($q) use ($fatherUpper, $fatherAscii) {
                    $q->where('father', 'ilike', '%' . $fatherUpper . '%')
                      ->orWhere('father', 'ilike', '%' . $fatherAscii . '%');
                });
            })
            ->when($request->mother, function ($query, $mother) {
                $motherUpper = Str::upper($mother);
                $motherAscii = Str::ascii($motherUpper);
                return $query->where(function($q) use ($motherUpper, $motherAscii) {
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
                
                return $query->where(function($q) use ($birthCityUpper, $birthCityAscii) {
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
                return $query->where(function($q) use ($tattooUpper, $tattooAscii) {
                    $q->where('tatto', 'ilike', '%' . $tattooUpper . '%')
                      ->orWhere('tatto', 'ilike', '%' . $tattooAscii . '%');
                });
            })
            ->when($request->orcrim, function ($query, $orcrim) {
                $orcrimUpper = Str::upper($orcrim);
                $orcrimAscii = Str::ascii($orcrimUpper);
                return $query->where(function($q) use ($orcrimUpper, $orcrimAscii) {
                    $q->where('orcrim', 'ilike', '%' . $orcrimUpper . '%')
                      ->orWhere('orcrim', 'ilike', '%' . $orcrimAscii . '%');
                });
            })
            ->when($request->area_atuacao, function ($query, $areaAtuacao) {
                $areaUpper = Str::upper($areaAtuacao);
                $areaAscii = Str::ascii($areaUpper);
                return $query->where(function($q) use ($areaUpper, $areaAscii) {
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
                    $subQuery->where(function($q) use ($phoneClean) {
                        // Busca por número completo (DDD + telefone concatenados)
                        $q->whereRaw("CONCAT(ddd, telephone) LIKE ?", ['%' . $phoneClean . '%'])
                          // Busca apenas pelo número do telefone (sem DDD)
                          ->orWhere('telephone', 'like', '%' . $phoneClean . '%');
                          
                        // Se o número tem 11 dígitos, separar DDD dos primeiros 2 dígitos
                        if (strlen($phoneClean) === 11) {
                            $ddd = substr($phoneClean, 0, 2);
                            $numero = substr($phoneClean, 2);
                            $q->orWhere(function($subQ) use ($ddd, $numero) {
                                $subQ->where('ddd', $ddd)
                                     ->where('telephone', 'like', '%' . $numero . '%');
                            });
                        }
                        // Se o número tem 10 dígitos, separar DDD dos primeiros 2 dígitos
                        elseif (strlen($phoneClean) === 10) {
                            $ddd = substr($phoneClean, 0, 2);
                            $numero = substr($phoneClean, 2);
                            $q->orWhere(function($subQ) use ($ddd, $numero) {
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
                    $subQuery->where(function($q) use ($cityUpper, $cityAscii) {
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
                    $subQuery->where(function($q) use ($placaNormalized) {
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
                    $subQuery->where(function($q) use ($naturezaUpper, $naturezaAscii) {
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
                return $query->whereHas('tjs', function ($subQuery) use ($situacao) {
                    $subQuery->where('situacao', $situacao);
                });
            });

        return $query->limit(50)->get();
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
                return $query->where(function($q) use ($nameUpper, $nameAscii) {
                    $q->where('name', 'ilike', '%' . $nameUpper . '%')
                      ->orWhere('name', 'ilike', '%' . $nameAscii . '%')
                      ->orWhere('nickname', 'ilike', '%' . $nameUpper . '%')
                      ->orWhere('nickname', 'ilike', '%' . $nameAscii . '%');
                });
            })
            ->when($request->cpf, function ($query, $cpf) {
                return $query->where('cpf', 'like', '%' . Str::upper($cpf) . '%');
            })
            ->when($request->rg, function ($query, $rg) {
                return $query->where('rg', 'like', '%' . Str::upper($rg) . '%');
            })
            ->when($request->father, function ($query, $father) {
                $fatherUpper = Str::upper($father);
                $fatherAscii = Str::ascii($fatherUpper);
                return $query->where(function($q) use ($fatherUpper, $fatherAscii) {
                    $q->where('father', 'ilike', '%' . $fatherUpper . '%')
                      ->orWhere('father', 'ilike', '%' . $fatherAscii . '%');
                });
            })
            ->when($request->mother, function ($query, $mother) {
                $motherUpper = Str::upper($mother);
                $motherAscii = Str::ascii($motherUpper);
                return $query->where(function($q) use ($motherUpper, $motherAscii) {
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
                
                return $query->where(function($q) use ($birthCityUpper, $birthCityAscii) {
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
                return $query->where(function($q) use ($tattooUpper, $tattooAscii) {
                    $q->where('tatto', 'ilike', '%' . $tattooUpper . '%')
                      ->orWhere('tatto', 'ilike', '%' . $tattooAscii . '%');
                });
            })
            ->when($request->orcrim, function ($query, $orcrim) {
                $orcrimUpper = Str::upper($orcrim);
                $orcrimAscii = Str::ascii($orcrimUpper);
                return $query->where(function($q) use ($orcrimUpper, $orcrimAscii) {
                    $q->where('orcrim', 'ilike', '%' . $orcrimUpper . '%')
                      ->orWhere('orcrim', 'ilike', '%' . $orcrimAscii . '%');
                });
            })
            ->when($request->area_atuacao, function ($query, $areaAtuacao) {
                $areaUpper = Str::upper($areaAtuacao);
                $areaAscii = Str::ascii($areaUpper);
                return $query->where(function($q) use ($areaUpper, $areaAscii) {
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
                            ->where(function($q) use ($phoneClean) {
                                // Busca por número completo (DDD + telefone concatenados)
                                $q->whereRaw("CONCAT(telephones.ddd, telephones.telephone) LIKE ?", ['%' . $phoneClean . '%'])
                                  // Busca apenas pelo número do telefone (sem DDD)
                                  ->orWhere('telephones.telephone', 'like', '%' . $phoneClean . '%');
                                  
                                // Se o número tem 11 dígitos, separar DDD dos primeiros 2 dígitos
                                if (strlen($phoneClean) === 11) {
                                    $ddd = substr($phoneClean, 0, 2);
                                    $numero = substr($phoneClean, 2);
                                    $q->orWhere(function($subQ) use ($ddd, $numero) {
                                        $subQ->where('telephones.ddd', $ddd)
                                             ->where('telephones.telephone', 'like', '%' . $numero . '%');
                                    });
                                }
                                // Se o número tem 10 dígitos, separar DDD dos primeiros 2 dígitos
                                elseif (strlen($phoneClean) === 10) {
                                    $ddd = substr($phoneClean, 0, 2);
                                    $numero = substr($phoneClean, 2);
                                    $q->orWhere(function($subQ) use ($ddd, $numero) {
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
                            ->where(function($q) use ($cityUpper, $cityAscii) {
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
                            ->where(function($q) use ($placaNormalized) {
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
                            ->where(function($q) use ($naturezaUpper, $naturezaAscii) {
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
                return $query->whereExists(function ($subQuery) use ($situacao) {
                    $subQuery->select(DB::raw(1))
                            ->from('tjs')
                            ->whereColumn('tjs.person_id', 'persons.id')
                            ->where('tjs.situacao', $situacao);
                });
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
            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C', 'Ñ' => 'N'
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
}
