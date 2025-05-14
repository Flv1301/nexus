<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ReverseLocationController extends Controller
{
    public function index()
    {
        return view('tools.reverse_location');
    }

    public function plotMap(Request $request)
    {
        // Validação dos arquivos, se necessário
        $request->validate([
            'file1' => 'required|mimes:csv',
            'file2' => 'sometimes|required_without:file1|mimes:csv',
            'file3' => 'sometimes|required_without:file1, file2|mimes:csv',
        ]);

        $cvsfilled = [];

        for ($i = 1; $i <= 3; $i++) {
            $fieldName = 'file' . $i;

            // Verifique se o campo de arquivo está presente e não é vazio
            if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) {
                $fileContent = $request->file($fieldName)->get();
                $cvsfilled[$fieldName] = $this->prepareCSV($fileContent);
            }
        }

        $idsArrays = [];
        $markers = [];

        foreach ($cvsfilled as $fileContent) {
            $idsArray = array_column($fileContent, 'Reverse Location Obfuscated ID');
            $idsArrays[] = $idsArray;
        }

        $idsRepetidos = call_user_func_array('array_intersect', $idsArrays);

        foreach ($cvsfilled as $fileContent) {
            foreach ($fileContent as $file) {
                if (in_array($file['Reverse Location Obfuscated ID'], $idsRepetidos)) {
                    $file['Color'] = $this->generateColor($file['Reverse Location Obfuscated ID']);
                    $markers[] = $file;
                }
            }
        }

        return view('tools.show', compact('markers','idsRepetidos'));
    }

    public function prepareCSV($fileContent)
    {
        $arraymarkers = explode("\n", $fileContent);
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

    public static function generateColor($id)
    {
        // Gere uma cor hexadecimal única com base no ID
        return '#' . substr(md5($id), 0, 6);
    }



}
