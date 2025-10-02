<?php

namespace App\APIs;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiBacenPix
{
    private const BASE_URL = 'https://www3.bcb.gov.br/bc_ccs/rest/';

    private ?string $username;
    private ?string $password;

    public function __construct()
    {
        $this->username = env("PIX_API_USERNAME") ?? null;
        $this->password = env("PIX_API_PASSWORD") ?? null;
    }

    /**
     * @param string $endpoint
     * @param array $queryParams
     * @return array|string
     * @throws ConnectionException
     */
    private function request(string $endpoint, array $queryParams): array|string
    {
        try {
            $url = self::BASE_URL . $endpoint;

            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->get($url, $queryParams);

            if ($response->successful()) {
                return $response->body();
            }

            return ['error' => $response->body()];
        } catch (ConnectionException $exception) {
            Log::error($exception->getMessage());
            return throw new ConnectionException('Não foi possível conectar o servidor.', $exception->getCode());
        }
    }

    /**
     * @param string $pixKey
     * @param string $motivation
     * @return array|string
     * @throws ConnectionException
     */
    public function searchByPixKey(string $pixKey, string $motivation): array|string
    {
        $endpoint = 'consultar-vinculo-pix';
        $queryParams = [
            'chave' => $pixKey,
            'motivo' => $motivation,
        ];

        return $this->request($endpoint, $queryParams);
    }

    /**
     * @param string $cpfCnpj
     * @param string $motivation
     * @return array|string
     * @throws ConnectionException
     */
    public function searchByCpfCnpj(string $cpfCnpj, string $motivation): array|string
    {
        $endpoint = 'consultar-vinculos-pix';
        $queryParams = [
            'cpfCnpj' => $cpfCnpj,
            'motivo' => $motivation,
        ];

        return $this->request($endpoint, $queryParams);
    }
}
