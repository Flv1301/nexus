<?php

namespace App\Services\Api\Public;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Ipify
{
    /**
     * @return string
     */
    public static function publicIP(): string
    {
        try {
            $client = new Client(['timeout' => 10]);
            $response = $client->get('https://api64.ipify.org');
            return $response->getBody();
        } catch (GuzzleException $exception) {
            Log::error($exception->getMessage());
            return '';
        }
    }
}
