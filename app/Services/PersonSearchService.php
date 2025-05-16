<?php

namespace App\Services;

use App\APIs\CortexApi;
use App\Models\Data\Address;
use App\Models\Person\Person;

class PersonSearchService
{
    /**
     * @param $id
     * @return mixed
     */
    public function person($id): mixed
    {
        return Person::find($id);
    }

    /**
     * @param $cpf
     * @return array|int[]|mixed|string[]
     */
    public function cortex($cpf): mixed
    {
        $response = (new CortexApi())->personSearchCPF($cpf);

        if (array_key_exists('error', $response)) {
            return ['error' => 'Não foi possível conectar a base de dados do Cortex!'];
        }
        return $response;
    }

    /**
     * @param $string
     * @return Address
     */
    protected function pregMatchAddress($string): Address
    {
        $address = new Address();
        $expression = '/^(.*?)\s+(\d+)\s+(.*?)\s+(.*?)\s+(.*?)$/';
        if (preg_match($expression, $string, $matches)) {
            $address->address = $matches[1];
            $address->number = $matches[2];
            $address->district = $matches[3];
            $address->city = $matches[4];
            $address->uf = $matches[5];
        }
        return $address;
    }
}
