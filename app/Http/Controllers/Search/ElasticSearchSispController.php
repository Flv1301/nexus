<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;

use App\Models\Sisp\Bop;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpWord\PhpWord;
use Symfony\Component\HttpClient\HttpClient;


use DateTime;
use Exception;


class ElasticSearchSispController extends Controller {

    /**
     * Load page search person
     * @return View
     */
    public function index(){
        return view('search.elastic.index');
    }

    public function show(Request $request){
        $bopKey = $request->bop;


        $bop = Bop::find($bopKey);

        return view('search.elastic.show',compact('bop'));
    }

//    public function elastic(Request $request)
//    {
//        try {
//            // Endereço do Elasticsearch
//            $elasticIP = 'http://10.77.11.11:9200/';
//
//            // Criar cliente Elasticsearch
//            $clientBuilder = ClientBuilder::create();
//            $clientBuilder->setHosts([$elasticIP]); // Define o IP do servidor Elasticsearch
//            $clientBuilder->setSSLVerification(false); // Desativa a verificação do certificado SSL
//            $client = $clientBuilder->build();
//
//            $params = [
//                'index' => 'bop_records',
//                'body' => [
//                    'query' => [
//                        'bool' => [
//                            'must' => []
//                        ]
//                    ],
//                    'size' => 1000 // Ajuste para tamanho máximo de resultados
//                ]
//            ];
//
//            // Adicionar condições à consulta com base nos campos enviados
//            $columns = $request->input('columns');
//            $operators = $request->input('operators');
//            $values = $request->input('values');
//
//            foreach ($columns as $key => $column) {
//                $operator = $operators[$key];
//                $value = $values[$key];
//
//                // Verificar se o valor não está vazio
//                if (!empty($value)) {
//                    switch ($operator) {
//                        case 'match':
//                            // Operador de correspondência
//                            $params['body']['query']['bool']['must'][] = [
//                                'match' => [
//                                    $column => $value
//                                ]
//                            ];
//                            break;
//
//                        case 'match_phrase':
//                            // Operador de correspondência de frase
//                            $params['body']['query']['bool']['must'][] = [
//                                'match_phrase' => [
//                                    $column => $value
//                                ]
//                            ];
//                            break;
//
//                        case 'prefix':
//                            // Operador de prefixo
//                            $params['body']['query']['bool']['must'][] = [
//                                'prefix' => [
//                                    $column => $value
//                                ]
//                            ];
//                            break;
//
//                        case 'wildcard':
//                            // Operador de curinga
//                            $params['body']['query']['bool']['must'][] = [
//                                'wildcard' => [
//                                    $column => '*' . $value . '*'
//                                ]
//                            ];
//                            break;
//
//                        default:
//                            // Operador de correspondência padrão
//                            $params['body']['query']['bool']['must'][] = [
//                                'match' => [
//                                    $column => $value
//                                ]
//                            ];
//                            break;
//                    }
//                }
//            }
//
//            // Executar a pesquisa
//            $response = $client->search($params);
//
//            // Acessar os resultados
//            $hits = $response['hits']['hits'];
//            dd($params);
//
//            return response()->json($hits);
//        } catch (NoNodesAvailableException $exception) {
//            return response()->json(['error' => $exception->getMessage()], 500);
//        }
//    }
//    public function elastic(Request $request)
//    {
//        try {
//            // Endereço do Elasticsearch
//            $elasticIP = 'http://10.77.11.11:9200/';
//
//            // Criar cliente Elasticsearch
//            $clientBuilder = ClientBuilder::create();
//            $clientBuilder->setHosts([$elasticIP]); // Define o IP do servidor Elasticsearch
//            $clientBuilder->setSSLVerification(false); // Desativa a verificação do certificado SSL
//            $client = $clientBuilder->build();
//
//            $params = [
//                'index' => 'bop_records',
//                'body' => [
//                    'query' => [
//                        'bool' => [
//                            'must' => []
//                        ]
//                    ],
//                    'size' => 1000 // Ajuste para tamanho máximo de resultados
//                ]
//            ];
//
//            // Adicionar condições à consulta com base nos campos enviados
//            $columns = $request->input('columns');
//            $operators = $request->input('operators');
//            $values = $request->input('values');
//
//            foreach ($columns as $key => $column) {
//                $operator = $operators[$key];
//                $value = $values[$key];
//
//                // Verificar se o valor não está vazio
//                if (!empty($value)) {
//                    // Verificar se o campo está dentro de bopenv
//                    $isBopenvField = strpos($column, 'bopenv.') === 0;
//
//                    switch ($operator) {
//                        case 'match':
//                        case 'match_phrase':
//                        case 'prefix':
////                        case 'range:gt':
////                        case 'range:gte':
////                        case 'range:lt':
////                        case 'range:lte':
//                        case 'wildcard':
//                            // Operadores que não requerem consulta nested
//                            $query = [
//                                $operator => [
//                                    $column => $isBopenvField ? $value : '*' . $value . '*'
//                                ]
//                            ];
//
//                            if ($isBopenvField) {
//                                $query = [
//                                    'nested' => [
//                                        'path' => 'bopenv',
//                                        'query' => [
//                                            $operator => [
//                                                 'bopenv.'.substr($column, 7) => $value
//                                            ]
//                                        ]
//                                    ]
//                                ];
//                            }
//
//                            $params['body']['query']['bool']['must'][] = $query;
//                            break;
//
//                        default:
//                            // Operador de correspondência padrão
//                            $params['body']['query']['bool']['must'][] = [
//                                'match' => [
//                                    $column => $value
//                                ]
//                            ];
//                            break;
//                    }
//                }
//            }
//
//            // Executar a pesquisa
//            $response = $client->search($params);
//
//            // Acessar os resultados
//            $hits = $response['hits']['hits'];
//            //dd($params);
//            dd($hits);
//
//            return response()->json($hits);
//        } catch (NoNodesAvailableException $exception) {
//            return response()->json(['error' => $exception->getMessage()], 500);
//        }
//    }

    public function elastic(Request $request)
    {
        try {
            $client = $this->buildElasticSearchClient();
            $params = $this->buildSearchParams($request);
            //dd($params);
            $response = $client->search($params);
            $hits = $response['hits']['hits'];
           // dd($hits);
          //$bops = response()->json($hits);
          $bops = $hits;

            return view('search.elastic.index', compact('bops'));
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }


    private function buildElasticSearchClient()
    {
        $elasticIP = 'http://10.77.11.11:9200/';
        $username = env('ELASTICSEARCH_USERNAME'); // Nome de usuário a partir do arquivo .env
        $password = env('ELASTICSEARCH_PASSWORD');
        $clientBuilder = ClientBuilder::create();
        $clientBuilder->setHosts([$elasticIP]);
        $clientBuilder->setSSLVerification(false);
        $clientBuilder->setBasicAuthentication($username, $password);

        return $clientBuilder->build();
    }

    private function buildSearchParams(Request $request)
    {
        $params = [
            'index' => 'bop_records',
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [],
                        'must_not' => [], // Para consultas must_not
                    ]
                ],
                'size' => 1000
            ]
        ];

        $columns = $request->input('columns');
        $operators = $request->input('operators');
        $values = $request->input('values');

        foreach ($columns as $key => $column) {
            $operator = $operators[$key];
            $value = $values[$key];
//            if($operator == 'query_string'){
//                $value = '*'.$value.'*';
//            }
            if ($operator == 'query_string') {
                $value = '"' . $value . '"'; // Usando aspas em vez de asteriscos
            }

            if (!empty($value)) {
                $isBopenvField = strpos($column, 'bopenv.') === 0;

                if ($isBopenvField) {
                    $this->handleNestedQuery($params, $column, $operator, $value);
                } elseif (str_starts_with($operator, 'range')) {
                    $this->handleRangeQuery($params, $column, $operator, $value);
                } elseif ($operator == 'term' || $operator == 'must_not') {
                    $this->handleTermQuery($params, $column, $operator, $value);
                }elseif ($operator == 'query_string') {
                    $this->handleQueryString($params, $column, $operator, $value);
                } else {
                    $this->handleOtherQuery($params, $column, $operator, $value);
                }
            }
        }
        //dd($params);
        return $params;
    }

    private function handleNestedQuery(&$params, $column, $operator, $value)
    {
        $nestedPath = explode('.', $column)[0];
        $nestedField = substr($column, strpos($column, '.') + 1);
        $query = [
            'nested' => [
                'path' => $nestedPath,
                'query' => [
                    'bool' => [
                        'must' => [
                            $operator => [
                                'bopenv.'. $nestedField => $value
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $params['body']['query']['bool']['must'][] = $query;
    }

    private function handleRangeQuery(&$params, $column, $operator, $value)
    {
        $rangeType = explode(':', $operator)[1];
        $formattedValue = $this->getFormattedValue($column, $value);
        $params['body']['query']['bool']['must'][] = [
            'range' => [
                $column => [
                    $rangeType => $formattedValue
                ]
            ]
        ];
    }

    private function handleTermQuery(&$params, $column, $operator, $value)
    {
        $queryType = $operator == 'term' ? 'must' : 'must_not';
        $params['body']['query']['bool'][$queryType][] = [
            'term' => [
                $column => $value
            ]
        ];
    }

    private function handleQueryString(&$params, $column, $operator, $value)
    {
        $params['body']['query']['bool']['must'][] = [
            'query_string' => [
                'query' => $value,
                'fields' => [$column]
            ]
        ];
    }
    private function handleOtherQuery(&$params, $column, $operator, $value)
    {
        $params['body']['query']['bool']['must'][] = [
            $operator => [
                $column => $value
            ]
        ];
    }

    private function getFormattedValue($column, $value)
    {
        // Verificar e formatar datas
        if ($column == 'dt_registro' || $column == 'dt_fato') {
            // Tentar detectar o formato da data e convertê-la para o formato ISO8601
            $date = $this->convertDateToISO8601($value);
            if ($date !== false) {
                return $date;
            } else {
                throw new Exception("Invalid date format for column {$column}: {$value}");
            }
        }
        return $value;
    }

    private function convertDateToISO8601($dateString)
    {
        $formats = ['Y-m-d H:i:s', 'd/m/Y'];
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $dateString);
            if ($date !== false && $date->format($format) == $dateString) {
                return $date->format('Y-m-d\TH:i:s');
            }
        }
        return false;
    }

    public function exportToWord(Request $request)
    {

        // Recupera os dados da sessão
        $bops = json_decode($request->bops);



        // Cria uma nova instância do PhpWord
        $phpWord = new PhpWord();

        // Cria uma seção no documento
        $section = $phpWord->addSection();

        // Adiciona um título
        $section->addTitle('HYDRA', 1);

        // Adiciona um parágrafo com os dados do $bops
        $section->addText('Dados do BOP:', ['bold' => true]);
        foreach ($bops as $bop) {

            $source = $bop->_source;
            $section->addText('BOP: ' . $source->n_bop);
            $section->addText('Data do Fato: ' . $source->data_fato);
            $section->addText('Unidade Responsável: ' . $source->unidade_responsavel);
            $section->addText('Relato: ' . $source->relato);
            $section->addText('ENVOLVIDOS');
            foreach ($bop->_source->bopenv as $env) {

                $section->addText('---');
                $section->addText($env->ds_atuacao. ': ' . $env->nm_envolvido);
                $section->addText('nascimento: ' . $env->nascimento);
                $section->addText('mãe: ' . $env->mae);
                $section->addText('pai: ' . $env->pai);
                $section->addText('cpf: ' . $env->cpf);
                $section->addText('contato: ' . $env->contato);
                $section->addText('endereco: ' . $env->localidade .'-'. $env->uf.' '. $env->endereco . ' Bairro '. $env->bairro. ' cep'. $env->cep );
                $section->addText('profissao: ' . $env->profissao);
                $section->addText('naturalidade: ' . $env->naturalidade);
                $section->addText('nacionalidade: ' . $env->nacionalidade);
                $section->addText('---');
            }
            $section->addText('------------------------------------------');
        }

        // Salva o documento como um arquivo temporário
        $tempFile = tempnam(sys_get_temp_dir(), 'phpword');
        $phpWord->save($tempFile);

        // Retorna o arquivo como resposta HTTP
        return response()->download($tempFile, 'bops.docx')->deleteFileAfterSend();
    }
}
