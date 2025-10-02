<?php

namespace App\APIs;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SynergyeApi
{

    /**
     * @var string
     */
    private static string $endpointPerson = 'https://chronos-api.synergye.com.br/api/v1/person/list';

    /**
     * @param string $id
     * @return mixed
     */
    public static function searchLocationById(string $id): mixed
    {
        try {
            return Http::timeout(15)->withHeaders([
                'endpoint' => SynergyeApi::$endpointPerson,
                'instance' => 'pa',
                'identificador' => $id,
                'k' => env('API_KEY_SYNERGYE')
            ])->withOptions(["verify" => false])->get(
                SynergyeApi::$endpointPerson,
                ['identificador' => $id, 'instance' => 'pa']
            )->json();
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return [];
        }
    }
}
