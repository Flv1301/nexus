<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 22/09/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ImageBlobCast implements CastsAttributes
{

    /**
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return string
     */
    public function get($model, string $key, $value, array $attributes): string
    {

        if (!$value) {
            return '';
        }

        return base64_encode(stream_get_contents($value));
    }

    /**
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        return $value;
    }
}
