<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 05/08/2023
 * @copyright NIP CIBER-LAB @2023
 */
namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

class UpCaseTextCast implements CastsAttributes
{
    /**
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        return $value;
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
        return Str::upper($value);
    }
}
