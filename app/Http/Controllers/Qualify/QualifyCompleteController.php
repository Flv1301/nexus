<?php
namespace App\Http\Controllers\Qualify;

use App\APIs\CortexApi;
use App\Http\Controllers\Controller;
use App\Models\Dpa\DPA;
use App\Models\Galton\Galton;
use App\Models\Seap\SEAP;
use App\Models\Seap\ViewSearchPriosoner;
use App\Models\Sisfac\Sisfac;
use App\Models\Sisp\BopEnv;
use App\Models\Sisp\SispView;
use App\Models\Srh\Srh;
use Illuminate\Http\Request;
set_time_limit(0);


class QualifyCompleteController extends Controller
{
    public function index()
    {
        return view('qualify.complete.index');

    }

    public function indexPost(Request $request)
    {


        if ($request->filled('campo') and $request->filled('operador') and $request->filled('valor')) {
            if($request->bd == 'seap'){
                $pesquisaSeap = [];
                for ($i = 0; $i < count($request->campo); $i++) {
                    $field = $request->campo[$i];
                    $operator = $request->operador[$i];
                    $value = $request->valor[$i];

                    if ($operator == "like") {
                        $value = "%" . $value . "%";
                    }

                    array_push($pesquisaSeap, [$field, $operator, strtoupper($value)]);
                }


                $presoanvancado = ViewSearchPriosoner::getPrisoner($pesquisaSeap);


                return view('qualify.seap.indexpost')->with("presoavancado", $presoanvancado);
            }elseif ($request->bd == 'sisp'){
                $pesquisaSisp = [];
                for ($i = 0; $i < count($request->campo); $i++) {
                    $field = $request->campo[$i];
                    $operator = $request->operador[$i];
                    $value = $request->valor[$i];

                    if ($operator == "like") {
                        $value = "%" . $value . "%";
                    }


                    array_push($pesquisaSisp, [$field, $operator, strtoupper($value)]);
                }


                $sispAvancado = SispView::getSisp($pesquisaSisp);


                return view('qualify.sisp.index_sisp_avancado')->with("sisps", $sispAvancado);

            }elseif ($request->bd == 'srh'){
                $pesquisaSrh = [];
                for ($i = 0; $i < count($request->campo); $i++) {
                    $field = $request->campo[$i];
                    $operator = $request->operador[$i];
                    $value = $request->valor[$i];

                    if ($operator == "like") {
                        $value = "%" . $value . "%";
                    }


                    array_push($pesquisaSrh, [$field, $operator, strtoupper($value)]);
                }


                $srhs = Srh::getEmployees($pesquisaSrh);


                return view('qualify.srh.index')->with("srhs", $srhs);

            }elseif ($request->bd == 'dpa'){
                $pesquisaDpa = [];
                for ($i = 0; $i < count($request->campo); $i++) {
                    $field = $request->campo[$i];
                    $operator = $request->operador[$i];
                    $value = $request->valor[$i];

                    if ($operator == "like") {
                        $value = "%" . $value . "%";
                    }


                    array_push($pesquisaDpa, [$field, $operator, strtoupper($value)]);
                }


                $dpas = DPA::getDpas($pesquisaDpa);


                return view('qualify.dpa.index')->with("dpas", $dpas);

            }elseif ($request->bd == 'galton'){
                $pesquisaGalton = [];
                for ($i = 0; $i < count($request->campo); $i++) {
                    $field = $request->campo[$i];
                    $operator = $request->operador[$i];
                    $value = $request->valor[$i];

                    if ($operator == "like") {
                        $value = "%" . $value . "%";
                    }


                    array_push($pesquisaGalton, [$field, $operator, strtoupper($value)]);
                }


                $galton = Galton::getGaltons($pesquisaGalton);


                return view('qualify.galton.index')->with("galton", $galton);

            }

        }


    }

    public function show(Request $request)
    {
        $complete = array();
        if ($request->filled('cpf')) {

            $seap = SEAP::getPrisonerByCPF($request->get('cpf'));
            if ($seap != null) {
                $complete = ['seap' => $seap];


            }

            $sisfac = Sisfac::getFacByCPF($request->get('cpf'));
            if ($sisfac != null) {
                $complete['sisfac'] = $sisfac;


            }
            $sisp = BopEnv::getBopsEnvByCPF($request->get('cpf'));
            if ($sisp != null) {
                $complete['sisp'] = $sisp;


            }
            $srh = Srh::getEmployeeByCPF($request->get('cpf'));
            if ($srh != null) {
                $complete['srh'] = $srh;


            }
            $galton = Galton::getGaltonbyCPF($request->get('cpf'));
            if ($galton != null) {

                $complete['galton'] = $galton;


            }
            $dpa = DPA::getDPAByCPF($request->get('cpf'));

            if ($dpa != null) {

                $complete['dpa'] = $dpa;


            }
            $cortexObj = new CortexApi();
            $cortex = $cortexObj->personSearchCPF($request->get('cpf'));
            dd($cortex);

            if ($cortex != null) {

                $complete['cortex'] = $cortex;


            }


            return view('qualify.complete.show')->with("complete", $complete);


        } elseif ($request->filled("nome") && $request->filled("nomeMae")) {
            $complete = array();
            $seap = SEAP::getPrisonerByMotherAndName($request->get('nome'), $request->get('nomeMae'));
            if ($seap != null) {
                $complete = ['seap' => $seap];

            }

            $sisfac = Sisfac::getFacByMotherAndName($request->get('nome'), $request->get('nomeMae'));
            if ($sisfac != null) {
                $complete['sisfac'] = $sisfac;
            }
            $sisp = BopEnv::getBopsEnvByMotherAndName($request->get('nome'), $request->get('nomeMae'));
            if ($sisp != null) {
                $complete['sisp'] = $sisp;


            }
            $srh = Srh::getEmployeeByName($request->get('nome'));
            if ($srh != null) {
                $complete['srh'] = $srh;


            }
            $galton = Galton::getGaltonByMotherAndName($request->get('nome'), $request->get('nomeMae'));
            if ($galton != null) {

                $complete['galton'] = $galton;


            }


            return view('qualify.complete.show')->with("complete", $complete);


        } elseif ($request->filled("nome") && $request->filled("dataNasc")) {
            $complete = array();
            $seap = SEAP::getPrisonerByNameAndBirthday($request->get('nome'), $request->get('dataNasc'));
            if ($seap != null) {
                $complete = ['seap' => $seap];


            }

            $sisfac = Sisfac::getFacByNameAndBirth($request->get('nome'), $request->get('dataNasc'));
            if ($sisfac != null) {
                $complete['sisfac'] = $sisfac;


            }
            $sisp = BopEnv::getBopsEnvByNameAndBirthday($request->get('nome'), $request->get('dataNasc'));
            if ($sisp != null) {
                $complete['sisp'] = $sisp;


            }
            $srh = Srh::getEmployeeByNameAndBirth($request->get('nome'), $request->get('dataNasc'));

            if ($srh != null) {
                $complete['srh'] = $srh;


            }
            $galton = Galton::getGaltonByNameAndBirth($request->get('nome'), $request->get('dataNasc'));
            if ($galton != null) {
                dd($galton);
                $complete['galton'] = $galton;
            }
            return view('qualify.complete.show')->with("complete", $complete);
        } else {

        }

        return view('qualify.complete.show');

    }
}
