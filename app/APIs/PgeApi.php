<?php

/**
 * Cliente HTTP simples para comunicação com APIs externas (PgeApi).
 */

namespace App\APIs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PgeApi
{
    private string $baseUri;
    private string $apiKey;

    public function __construct(?string $baseUri = null, ?string $apiKey = null)
    {
        $raw = $baseUri ?? env('EXTERNAL_PGE_API_URI', '');
        // Normaliza para esquema + host (sem path) para seguir a opção A recomendada.
        if (!empty($raw)) {
            $parts = parse_url($raw);
            if ($parts !== false && isset($parts['scheme']) && isset($parts['host'])) {
                $port = isset($parts['port']) ? ':' . $parts['port'] : '';
                $this->baseUri = $parts['scheme'] . '://' . $parts['host'] . $port;
            } else {
                $this->baseUri = rtrim($raw, '/');
            }
        } else {
            $this->baseUri = '';
        }
        $this->apiKey = $apiKey ?? env('EXTERNAL_PGE_API_KEY', '');
    }

    private function headers(): array
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        if (!empty($this->apiKey)) {
            if (preg_match('/^Bearer\s+/i', $this->apiKey)) {
                $headers['Authorization'] = $this->apiKey;
            } else {
                $headers['X-API-KEY'] = $this->apiKey;
            }
        }

        return $headers;
    }

    public function get(string $path, array $query = []): array
    {
        try {
            $url = $this->buildUrl($path);
            $response = Http::withHeaders($this->headers())
                ->timeout(10)
                ->retry(2, 100)
                ->get($url, $query);

            return $this->formatResponse($response->status(), $response->body(), $response->json());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function post(string $path, array $data = [], array $headers = []): array
    {
        try {
            $url = $this->buildUrl($path);
            $mergedHeaders = array_merge($this->headers(), $headers);
            $response = Http::withHeaders($mergedHeaders)
                ->timeout(15)
                ->retry(2, 100)
                ->post($url, $data);

            return $this->formatResponse($response->status(), $response->body(), $response->json());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    private function buildUrl(string $path): string
    {
        $path = ltrim($path, '/');
        return $this->baseUri === '' ? $path : ($this->baseUri . '/' . $path);
    }

    private function formatResponse(int $status, string $body, $json)
    {
        if ($status >= 200 && $status < 300) {
            return is_array($json) ? $json : ['data' => $json];
        }

        return ['error' => $body, 'status' => $status];
    }
}
