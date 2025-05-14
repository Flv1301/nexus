<?php

namespace App\Helpers;

class FeatureHelper
{
    public static function changeFeature($feature){
        if ($feature['geometry']['type'] == 'Point') {
                $varName = 'marker'.rand(1,1000000);
            $mapResponse = "";
            if(isset($feature['properties']['latitude'])){
                   $mapResponse = 'var marker = L.marker(['.$feature['properties']['latitude'].','.$feature['properties']['longitude'].']).addTo(map);' ;
                   $mapResponse.= ' map.setView([ '.$feature['properties']['latitude'].','.$feature['properties']['longitude'].'], 10);';

            }


            if($feature['geometry']['coordinates'][0] > -20){
             $mapResponse.=' var '.$varName.' = L.marker(['.$feature['geometry']['coordinates'][0].', '.$feature['geometry']['coordinates'][1].']).addTo(map);';
             $mapResponse.= ' map.setView([ '.$feature['geometry']['coordinates'][0].','.$feature['geometry']['coordinates'][1].'], 10);';
             return $mapResponse;
            }else{

               $mapResponse.=' var '.$varName.' = L.marker(['.$feature['geometry']['coordinates'][1].', '.$feature['geometry']['coordinates'][0].']).addTo(map);';
                $mapResponse.= ' map.setView([ '.$feature['geometry']['coordinates'][1].','.$feature['geometry']['coordinates'][0].'], 10);';
                return $mapResponse;
            }


        }else if($feature['geometry']['type'] == 'CÍRCULO'){
                $circleName =   'circle'.rand(1,1000000);
                $strCircle = "var ".$circleName." = L.circle([";
            if($feature['geometry']['coordinates'][0][0] > -20) {
                $strCircle.= $feature['geometry']['coordinates'][0][0] . ", " . $feature['geometry']['coordinates'][0][1] . "]";
            }else{
                $strCircle.=  $feature['geometry']['coordinates'][0][1] . ", " . $feature['geometry']['coordinates'][0][0] . "]";

            }
            if($feature['properties']['tipo'] == 'EXCLUSÃO'){
               $strCircle .= ", { color: 'red', fillColor: '#f03', fillOpacity: 0.5, radius: 50}).addTo(map);";
            }else if($feature['properties']['tipo'] == 'INCLUSÃO'){
               $strCircle .= ", { color: 'green', fillColor: '#0f0', fillOpacity: 0.5, radius: 100}).addTo(map);";

            }else{
               $strCircle .= ", { color: 'blue', fillColor: '#00f', fillOpacity: 0.5, radius: 50}).addTo(map);";
            }


            $strCircle.= $circleName.'.bindPopup("Descrição: '.$feature['properties']['descricao'].', Tipo: '.$feature['properties']['tipo'].'");';

            return $strCircle;
        }else if($feature['geometry']['type'] == 'POLÍGONO'){

            $polygonName = 'polygon'.rand(1,1000000);
            $strVar =  "var ".$polygonName." = L.polygon([";
            foreach ($feature['geometry']['coordinates'] as $coods){
                if($coods[0] > -20){
                    $strVar.= "[".$coods[0].", ".$coods[1]."],";
                }else{

                $strVar.= "[".$coods[1].", ".$coods[0]."],";
                }
            }
            $strVar = mb_substr($strVar, 0, -1);
            $strVar.= "]).addTo(map);";
            $strVar.= $polygonName.'.bindPopup("Descrição: '.$feature['properties']['descricao'].', Tipo: '.$feature['properties']['tipo'].'");';
            return $strVar;


        }



    }

}
