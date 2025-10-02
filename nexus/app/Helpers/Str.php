<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Str
{
    /**
     * @param Request $request
     * @param array $inputs
     * @return void
     */
    public static function asciiRequest(Request $request, array $inputs = []): void
    {
        array_walk_recursive($inputs, fn(&$value) => $value = \Illuminate\Support\Str::ascii($value));
        $request->merge($inputs);
    }

    /**
     * @param Request $request
     * @param array $inputs
     * @return void
     */
    public static function upperRequest(Request $request, array $inputs = []): void
    {
        array_walk_recursive($inputs, fn(&$value) => $value = \Illuminate\Support\Str::upper($value));
        $request->merge($inputs);
    }

    /**
     * @param Request $request
     * @param array $input
     * @return void
     */
    public static function lowerRequest(Request $request, array $input = []): void
    {
        array_walk_recursive($input, fn(&$value) => $value = \Illuminate\Support\Str::lower($value));
        $request->merge($input);
    }

    /**
     * @param string $phoneNumber
     * @return array
     */
    public static function parsePhoneNumberBr(string $phoneNumber): array
    {
        $phoneNumber = str_replace('+', '', $phoneNumber);
        $lenght = strlen($phoneNumber);
        $ddd = '';
        $countryCode = '';

        if ($lenght == 9) {
            $number = substr($phoneNumber, -9);
        } elseif ($lenght == 10) {
            $number = substr($phoneNumber, -8);
            $ddd = substr($phoneNumber, 0, -8,);
        } elseif ($lenght == 11) {
            $number = substr($phoneNumber, -9);
            $ddd = substr($phoneNumber, 0, -9,);
        } elseif ($lenght == 12) {
            $number = substr($phoneNumber, -8);
            $ddd = substr($phoneNumber, 2, -8);
            $countryCode = substr($phoneNumber, 0, -10);
        } elseif ($lenght == 13) {
            $number = substr($phoneNumber, -9);
            $ddd = substr($phoneNumber, 2, -9);
            $countryCode = substr($phoneNumber, 0, -11);
        } else {
            $number = substr($phoneNumber, -8);
        }

        return compact('countryCode', 'ddd', 'number');
    }

    /**
     * @param string $date
     * @return string
     */
    public static function convertDateToEnUs(string $date): string
    {
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

}
