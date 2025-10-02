<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 20/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\APIs;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class EscavadorApi
{
    /**
     * @var string|mixed
     */
    private static string $token;
    /**
     * @var string
     */
    private static string $endpoint = 'https://api.escavador.com/api/v1/';

    public function __construct()
    {
        self::$token = env('escavador_api_key');
    }

    /**
     * @param string $terms
     * @param string $options
     * @param string $limit
     * @param int $page
     * @return array|mixed
     */
    public function termsSearch(string $terms, string $options = 't', string $limit = '20', int $page = 1): mixed
    {
        $data = ['q' => $terms, 'qo' => $options, 'limit' => $limit, 'page' => $page];
        return $this->send()->get(self::$endpoint . 'busca?' . http_build_query($data))->json();
    }

    public function personSearch(string $terms, string $options = 't', string $limit = '20', int $page = 1): mixed
    {
        $data = ['q' => $terms, 'qo' => $options, 'limit' => $limit, 'page' => $page];
        return $this->send()->get(self::$endpoint . 'busca?' . http_build_query($data))->json();
    }

    /**
     * @return PendingRequest
     */
    private function send(int $timeout = 15): PendingRequest
    {
        return Http::timeout($timeout)->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . self::$token,
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
    }
}
