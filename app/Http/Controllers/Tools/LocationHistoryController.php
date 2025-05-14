<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class LocationHistoryController extends Controller
{
    public function index()
    {
        return view('tools.location-history');
    }

    public function plotMap(Request $request)
    {

        $fileContent = $request->file('file1')->get();
        $csvData = $this->prepareCSV($fileContent);

        $startDate = $request->filled('start_date') ? strtotime($request->input('start_date')) : null;
        $endDate = $request->filled('end_date') ? strtotime($request->input('end_date')) : null;
        $gpsChecked = $request->has('gps');
        $wifiChecked = $request->has('wifi');
        $cellChecked = $request->has('cell');
        $maxDistance = $request->filled('distance') ? $request->input('distance') : null;

        $filteredData = [];

        foreach ($csvData as $row) {
            $timestamp = strtotime(str_replace(['T', 'Z'], [' ', ''], $row['Timestamp (UTC)']));
            $source = strtoupper($row['Source']);
            $radius = intval($row['Display Radius (Meters)']);
            $latitude = floatval($row['Latitude']);
            $longitude = floatval($row['Longitude']);

            // Verifica se o timestamp está dentro do intervalo
            $withinDateRange = (!$startDate || $timestamp >= $startDate) && (!$endDate || $timestamp <= $endDate);

            // Verifica se o SOURCE está selecionado
            $validSource = ($gpsChecked && $source === 'GPS') || ($wifiChecked && $source === 'WIFI') || ($cellChecked && $source === 'CELL');

            // Verifica se o display radius é menor que a distância informada pelo usuário
            $validRadius = (!$maxDistance || $radius < $maxDistance);

            // Se todas as condições forem atendidas, adiciona os dados filtrados
            if ($withinDateRange && $validSource && $validRadius) {
                $markers[] = [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'display_radius' => $radius,
                    'timestamp' => $timestamp,
                    'source' => $source
                ];
            }
        }


        return view('tools.show-map-history', compact('markers'));
    }

    public function prepareCSV($fileContent)
    {
        $arraymarkers = explode("\n", $fileContent);
        array_shift($arraymarkers);
        $strheader = array_shift($arraymarkers);
        $header = str_getcsv($strheader, ',', '"',);
        $markers = [];

        foreach ($arraymarkers as $rows) {
            $arrayrow = str_getcsv($rows, ',', '"',);
            if (count($header) == count($arrayrow) and count($arrayrow) != 0) {
                array_push($markers, array_combine($header, $arrayrow));
            }
        }

        return $markers;
    }
//
//    public static function generateColor($id)
//    {
//        // Gere uma cor hexadecimal única com base no ID
//        return '#' . substr(md5($id), 0, 6);
//    }



}
