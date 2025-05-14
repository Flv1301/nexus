<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 05/08/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class DateCast implements CastsAttributes
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

        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $value)) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y H:i:s');
        }

        return $value ? Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y') : '';
    }

    /**
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return mixed|string
     */
    public function set($model, string $key, $value, array $attributes): mixed
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}$/', $value)) {
            return Carbon::createFromFormat('d/m/Y H:i:s', $value)->toDateString();
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }

        return '';
    }

}
