<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Erb extends Model
{
    use HasFactory;

    protected $table = "erbs";
    protected $connection = "pgsql";

    public static function desenharPoligonos($erbs)
    {
        $visitadas = [];

        foreach ($erbs as $erb) {
            if (!in_array($erb, $visitadas)) {
                $vizinhas = self::encontrarVizinhas($erb, $erbs);

                if (count($vizinhas) >= 3) {
                    foreach ($vizinhas as $vizinha) {
                        $polygonCoordinates = self::calcularHexagono($vizinha['latitude'], $vizinha['longitude'], 1);


                        $coordinatesJSON = json_encode($polygonCoordinates);
                        $color = self::gerarCorAleatoria();


                        $coordinatesJSON = json_encode($polygonCoordinates);
                        echo "var polygon = L.polygon($coordinatesJSON, { color: '$color' }).addTo(map);";
                        echo "poligonos.push(polygon);";
                    }
                }

                $visitadas = array_merge($visitadas, $vizinhas);
            }
        }
    }

    public static function importMarkersGoogle($csv)
    {
        $arraymarkers = explode("\n", $csv);
        //guarda o cabeçalho
        $strheader = array_shift($arraymarkers);
        //tranforma o cabeçalho em array
        $header = str_getcsv($strheader, ',', '"',);

        $markers = array();

        foreach ($arraymarkers as $rows) {

            $arrayrow = str_getcsv($rows, ',', '"',);
            if (count($header) == count($arrayrow) and count($arrayrow) != 0) {


                array_push($markers, array_combine($header, $arrayrow));
            }

        }
        dd($markers);

    }

    public static function gerarCorAleatoria()
    {
        $letters = '0123456789ABCDEF';
        $color = '#';

        for ($i = 0; $i < 6; $i++) {
            $color .= $letters[rand(0, 15)];
        }

        return $color;
    }


    public static function calcularHexagono($lat, $lng, $radius)
    {
        $coordinates = [];
        $center = ['lat' => $lat, 'lng' => $lng];

        // Convert the radius from km to degrees
        $radiusDegrees = $radius / 111.32;

        // Calculate the coordinates of the six corners of the hexagon
        for ($i = 0; $i < 6; $i++) {
            $angle = 2 * M_PI / 6 * $i;
            $cornerLat = $center['lat'] + $radiusDegrees * cos($angle);
            $cornerLng = $center['lng'] + $radiusDegrees * sin($angle);
            $coordinates[] = [$cornerLat, $cornerLng];
        }

        return $coordinates;
    }

    public static function encontrarVizinhas($erb, $erbs)
    {
        $lat = $erb['lat'];
        $lng = $erb['lng'];

        $vizinhas = [];

        foreach ($erbs as $outraErb) {
            $outraLat = $outraErb['lat'];
            $outraLng = $outraErb['lng'];

            // Verifique se a ERB não é a mesma e se está próxima o suficiente
            if ($outraErb !== $erb && self::calcularDistancia($lat, $lng, $outraLat, $outraLng) < 100) {
                $vizinhas[] = $outraErb;
            }
        }

        return $vizinhas;
    }

    public static function calcularDistancia($lat1, $lng1, $lat2, $lng2)
    {
        // Código para calcular a distância entre duas coordenadas

        $earthRadius = 6371; // Raio médio da Terra em quilômetros

        $latDiff = deg2rad($lat2 - $lat1);
        $lngDiff = deg2rad($lng2 - $lng1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDiff / 2) * sin($lngDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }

    public static function getErbs($city, $operator)
    {
        return Erb::query()->where('municipio', '=', $city)->where('operadora', '=', $operator)->get();
    }

    public static function getCity()
    {
        return Erb::query()
            ->distinct()
            ->orderBy('municipio', 'asc')
            ->pluck('municipio')
            ->toArray();
    }


}
