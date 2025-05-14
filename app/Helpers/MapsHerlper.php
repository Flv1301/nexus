<?php
/**
 * @author Herbety Thiago Maciel
 * @version 1.0
 * @since 12/04/2023
 * @copyright NIP CIBER-LAB @2023
 */

namespace App\Helpers;

class MapsHerlper
{
    /**
     * @param string $coordinate
     * @return float|string
     */
//    public static function coordinateConverterGi2(string $coordinate): float|string
//    {
//        if (!$coordinate){
//            return '';
//        }
//        $parts = preg_split('/\s+/', $coordinate);
//
//        $direction = $parts[0];
//        $coordinate = $parts[1];
//        $sign = ($direction === "Sul" || $direction === "Oeste") ? -1 : 1;
//        $pattern = "/(\d+)°(\d+)'([\d\.]+)\"/";
//        preg_match($pattern, $coordinate, $matches);
//        $degrees = (float) $matches[1];
//        $minutes = (float) $matches[2];
//        $seconds = (float) $matches[3];
//        $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
//        $decimal *= $sign;
//        return $decimal;
//    }

    public static function coordinateConverterGi2($coordinate): float|string
    {
        if (is_numeric($coordinate)) {
            // Se for um valor decimal, retorne o valor diretamente
            return (float)$coordinate;
        } else {

            if (!$coordinate) {
                return '';
            }
            $parts = preg_split('/\s+/', $coordinate);

            // Verifica se o array possui pelo menos dois elementos
            if (count($parts) < 2) {
                return '';
            }

            $direction = $parts[0];
            $coordinate = $parts[1];
            $sign = ($direction === "Sul" || $direction === "Oeste") ? -1 : 1;
            $pattern = "/(\d+)°(\d+)'([\d\.]+)\"/";
            preg_match($pattern, $coordinate, $matches);

            // Verifica se o padrão foi encontrado antes de acessar $matches[1]
            if (!isset($matches[1])) {
                return '';
            }

            $degrees = (float)$matches[1];
            $minutes = (float)$matches[2];
            $seconds = (float)$matches[3];
            $decimal = $degrees + ($minutes / 60) + ($seconds / 3600);
            $decimal *= $sign;
            return $decimal;
        }
    }

}
