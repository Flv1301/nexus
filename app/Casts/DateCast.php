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
        // Se o valor está vazio ou é null, retorna null
        if (empty($value) || $value === '') {
            return null;
        }

        // Se o valor já está no formato Y-m-d, retorna como está
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        // Se o valor está no formato d/m/Y H:i:s
        if (preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}$/', $value)) {
            try {
                return Carbon::createFromFormat('d/m/Y H:i:s', $value)->toDateString();
            } catch (\Exception $e) {
                // Se falhar na conversão, tenta outros formatos
            }
        }

        // Se o valor está no formato d/m/Y
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            try {
                return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
            } catch (\Exception $e) {
                // Se falhar na conversão, tenta outros formatos
            }
        }

        // Tenta parsing automático do Carbon para outros formatos
        if (!empty($value)) {
            try {
                $carbonDate = Carbon::parse($value);
                return $carbonDate->toDateString();
            } catch (\Exception $e) {
                // Se ainda falhar, retorna null para evitar erro de inserção
                return null;
            }
        }

        return null;
    }

}
