<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 06/02/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\APIs;

use App\Enums\UFBrEnum;
use Canducci\ZipCode\Facades\ZipCode;
use Canducci\ZipCode\ZipCodeException;

class ZipCodeBr
{
    /**
     * @param string $code
     * @return array
     */
    public static function zipCode(string $code)
    {
        try {
            $api = ZipCode::find($code, true);
            if ($api) {
                $api = $api->getobject();
                $data = [];
                $data['code'] = $api->cep;
                $data['address'] = $api->logradouro;
                $data['district'] = $api->bairro;
                $data['city'] = $api->localidade;
                $data['state'] = UFBrEnum::tryFrom($api->uf)->state();
                $data['uf'] = $api->uf;
                return $data;
            }
            return [];
        } catch (ZipCodeException $exception) {
            return [];
        }
    }
}
