<?php

namespace App\Helpers;

class DataFormatHelper
{
    public $dia;
    public $semana;
    public $mes;
    public $ano;


    public function getHelper(){
        $data = date('D');
        $mes = date('M');
        $dia = date('d');
        $ano = date('Y');


        $mes_extenso = array(
            'Jan' => 'Janeiro',
            'Feb' => 'Fevereiro',
            'Mar' => 'Marco',
            'Apr' => 'Abril',
            'May' => 'Maio',
            'Jun' => 'Junho',
            'Jul' => 'Julho',
            'Aug' => 'Agosto',
            'Nov' => 'Novembro',
            'Sep' => 'Setembro',
            'Oct' => 'Outubro',
            'Dec' => 'Dezembro'
        );

        $semana = array(
            'Sun' => 'Domingo',
            'Mon' => 'Segunda-Feira',
            'Tue' => 'Terca-Feira',
            'Wed' => 'Quarta-Feira',
            'Thu' => 'Quinta-Feira',
            'Fri' => 'Sexta-Feira',
            'Sat' => 'Sábado'
        );
            $this->dia = $dia;
            $this->mes = $mes_extenso["$mes"];
            $this->semana = $semana["$data"];
            $this->ano =  $ano;
            return $this;
    }





}
