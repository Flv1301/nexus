<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AquariumController extends Controller
{
    public function index()
    {

         // $presos = $this->monitoradosComMandados();

         $presos = $this->BNMPComEquatorial();
         //$presos = $this->presosComEquatorial();





        return view('aquarium.index',compact('presos'));
    }

    public function presosComMandados()
    {
//        $presosComMandados = DB::connection('pgsql2')
//            ->table('seap.preso')
//            ->join('polinter.mandados', function ($join) {
//                $join->on('seap.preso.preso_nome', '=', 'polinter.mandados.nome');
//            })
//            ->select('seap.preso.*')
//            ->where('seap.preso.situacaopreso_descricao', '=', 'PRESO')
//            ->where('polinter.mandados.status','=','EM ABERTO')
//            ->where('polinter.mandados.tipificacao','<>','RECAPTURA')
//            ->distinct()
//            ->get();
        $presosComMandados = DB::connection('pgsql2')->select("
    SELECT DISTINCT ON (p.preso_nome) p.*
    FROM seap.preso p
    INNER JOIN polinter.mandados m ON p.preso_nome = m.nome
    WHERE p.situacaopreso_descricao = 'PRESO'
    AND m.status = 'EM ABERTO'
    AND m.tipificacao <> 'RECAPTURA'
");

        return $presosComMandados;
    }

    public function presosComEquatorial()
    {

        $presosComMandados = DB::connection('pgsql2')->select("
    SELECT DISTINCT ON (p.nome) p.*, m.*
    FROM equatorial.equatorial p
    INNER JOIN polinter.mandados m ON p.nome = m.nome
    WHERE  m.status = 'EM ABERTO'
");

        return $presosComMandados;
    }
    public function BNMPComEquatorial()
    {

        $presosComMandados = DB::connection('pgsql2')->select("
    SELECT DISTINCT ON (p.nome) p.*, m.*
    FROM equatorial.equatorial p
    INNER JOIN bnmp.bnmp m ON p.nome = m.nome
");

        return $presosComMandados;
    }

    public function monitoradosComMandados()
    {
//        $presosComMandados = DB::connection('pgsql2')
//            ->table('seap.preso')
//            ->join('polinter.mandados', function ($join) {
//                $join->on('seap.preso.preso_nome', '=', 'polinter.mandados.nome');
//            })
//            ->select('seap.preso.*')
//            ->where('seap.preso.situacaopreso_descricao', '=', 'PRESO')
//            ->where('polinter.mandados.status','=','EM ABERTO')
//            ->where('polinter.mandados.tipificacao','<>','RECAPTURA')
//            ->distinct()
//            ->get();
        $presosComMandados = DB::connection('pgsql2')->select("
    SELECT DISTINCT ON (p.preso_nome) p.*
    FROM seap.preso p
    INNER JOIN bnmp.bnmp m ON p.preso_nome = m.nome
    WHERE p.situacaopreso_descricao = 'EM MONITORACAO'
");

        return $presosComMandados;
    }


}
