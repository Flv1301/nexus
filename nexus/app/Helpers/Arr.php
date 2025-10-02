<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class Arr
{

    /**
     *
     * @param $array
     * @param float $depth
     * @return array
     */
    public static function flatten($array, float $depth = INF): array
    {
        $result = [];
        foreach ($array as $key => $item) {
            $item = $item instanceof Collection ? $item->all() : $item;
            if (!is_array($item)) {
                $result[$key] = $item;
            } else {
                $values = $depth === 1
                    ? array_values($item)
                    : static::flatten($item, $depth - 1);
                foreach ($values as $key => $value) {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }

    /**
     * @param $array
     * @return array
     */
    public static function fllatenDuplicateKey($array): array
    {
        $tmp = [];
        $result = [];
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                foreach ($item as $key => $value) {
                    if (array_key_exists($key, $tmp)) {
                        $result[] = $tmp;
                        $tmp = [];
                    }
                    $tmp[$key] = $value;
                }
            }
        }
        $result[] = $tmp;
        return $result;
    }

    /**
     * @param array $array
     * @param int $length
     * @return array
     */
    public static function divideArray100(array $array, int $length = 100): array
    {
        $data = [];
        for ($i = 0; $i < count($array); $i += $length) {
            $data[] = array_slice($array, $i, $i + $length);
        }
        return $data;
    }
}
