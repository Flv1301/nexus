<?php

namespace App\APIs;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CacadorRoraimaApi
{
    private static string $endPoint = 'https://cacador.policiacivil.rr.gov.br/cacador/webservice_php_json/index.php';
    /** @var string */
    private static string $uriPerson = '?consulta_pessoa_nominal';
    /** @var string */
    private static string $uriPersonShow = '?consulta_pessoa_id';
    /** @var string */
    private static string $uriImage = '?consulta_pessoa_imagem';

    private ?string $username;
    private ?string $password;

    public function __construct()
    {
        $this->username = env("CASSADOR_API_USERNAME") ?? null;
        $this->password = env("CASSADOR_API_PASSWORD") ?? null;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function personSearch(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'mother' => 'nullable|string|max:255',
            'cpf' => 'nullable|string|size:11'
        ]);

        $name = $request->filled('name') ? $request->name : 'empty';
        $mother = $request->filled('mother') ? $request->mother : 'empty';
        $cpf = $request->filled('cpf') ? $request->cpf : 'empty';

        try {
            $url = self::$endPoint . self::$uriPerson;
            $response = Http::timeout(15)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'usuario' => $this->username,
                    'senha' => $this->password,
                    'nome' => $name,
                    'nome_mae' => $mother,
                    'numero_cpf' => $cpf,
                ])->json();

            if ($response['status'] === 200) {
                return response()->json($response['pessoa_fisica'], ResponseAlias::HTTP_OK);
            } else {
                Log::error('Resposta inesperada do serviço externo.', ['response' => $response]);
                return response()->json('Nenhum registro encontrado.', ResponseAlias::HTTP_NOT_FOUND);
            }
        } catch (RequestException $exception) {
            Log::error('Erro interno no servidor.', ['error' => $exception->getMessage()]);
            return response()->json(['error' => 'Erro interno no servidor.'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function personShow(int $id): JsonResponse
    {
        $url = self::$endPoint . self::$uriPersonShow;
        try {
            $response = Http::timeout(15)->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'usuario' => $this->username,
                    'senha' => $this->password,
                    'codigo_pessoa' => $id
                ])->json();

            if ($response['status'] === 200) {
                return response()->json($response['pessoa_fisica'], ResponseAlias::HTTP_OK);
            } else {
                Log::error('Resposta inesperada do serviço externo.', ['response' => $response]);
                return response()->json('Não foi possível processar a solicitação.', ResponseAlias::HTTP_BAD_GATEWAY);
            }

        } catch (RequestException $exception) {
            Log::error('Erro interno no servidor.', ['error' => $exception->getMessage()]);
            return response()->json('Erro interno no servidor.', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function personImage(int $id): JsonResponse
    {
        try {
            $url = self::$endPoint . self::$uriImage;
            $response = Http::timeout(15)->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, [
                    'usuario' => $this->username,
                    'senha' => $this->password,
                    'codigo_pessoa' => $id
                ])->json();

            if ($response['status'] === 200) {
                return response()->json($response['pessoa_fisica'], ResponseAlias::HTTP_OK);
            } else {
                Log::error('Resposta inesperada do serviço externo.', ['response' => $response]);
                return response()->json('Não foi possível processar a solicitação.', ResponseAlias::HTTP_BAD_GATEWAY);
            }

        } catch (RequestException $exception) {
            Log::error('Erro interno no servidor.', ['error' => $exception->getMessage()]);
            return response()->json('Erro interno no servidor.', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
